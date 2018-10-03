<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ExpensetypeEditController extends AbstractController
{
    /**
     * @Route("/expensetype/edit", name="expensetype_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $name = $request->request->get('name');

      if(isset($name) && !empty($name) && isset($id) && !empty($id)) {
        $typeOfExpense = $this->getDoctrine()
        ->getRepository(TypeOfExpense::class)
        ->find($id);
        if(!$typeOfExpense) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          $name = trim($name);
          $name = ucwords($name);
          $typeOfExpense->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($typeOfExpense);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $typeOfExpense->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Cannot change type of expense because new record already exists in database'));
          }

        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
