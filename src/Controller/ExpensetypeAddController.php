<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class ExpensetypeAddController extends AbstractController
{
    /**
     * @Route("/expensetype/add", name="expensetype_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $name = trim($name);
          $name = ucwords($name);
          $typeOfExpense = new TypeOfExpense();
          $typeOfExpense->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($typeOfExpense);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $typeOfExpense->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Type of expense is already in database.'));
          }
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
