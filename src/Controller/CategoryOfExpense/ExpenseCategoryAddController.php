<?php

namespace App\Controller\CategoryOfExpense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class ExpenseCategoryAddController extends AbstractController
{
    /**
     * @Route("/expensecategory/add", name="expensecategory_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $name = trim($name);
          $name = ucwords($name);
          $categoryOfExpense = new CategoryOfExpense();
          $categoryOfExpense->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($categoryOfExpense);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $categoryOfExpense->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Expense\' category is already in database.'));
          }
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
