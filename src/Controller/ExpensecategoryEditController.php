<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ExpensecategoryEditController extends AbstractController
{
    /**
     * @Route("/expensecategory/edit", name="expensecategory_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $name = $request->request->get('name');

      if(isset($name) && !empty($name) && isset($id) && !empty($id)) {
        $categoryOfExpense = $this->getDoctrine()
        ->getRepository(CategoryOfExpense::class)
        ->find($id);
        if(!$categoryOfExpense) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          $name = trim($name);
          $name = ucwords($name);
          $categoryOfExpense->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($categoryOfExpense );
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $categoryOfExpense->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Cannot change expense\'s category because new record already exists in database'));
          }

        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
