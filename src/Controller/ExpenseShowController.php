<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;

class ExpenseShowController extends AbstractController
{
    /**
     * @Route("/expense/show", name="expense_show")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $expense = $this->getDoctrine()
          ->getRepository(Expense::class)
          ->find($id);
          if(!$expense) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $id = $expense->getId();
            $date = $expense->getPurchaseDate();
            $product_id = $expense->getProductId();
            $description = $expense->getDescription();
            $price = $expense->getPrice();
            $weight = $expense->getWeight();
            $quantity = $expense->getQuantity();
            $place_id = $expense->getPlaceId()->getId();
            $payment_method_id = $expense->getPaymentMethodId();
            $type_of_expense_id = $expense->getTypeOfExpenseId();
            $comment = $expense->getComment();
            $category_of_expense_id = $expense->getCategoryOfExpenseId();
            $objects = array();
            array_push($objects, array(
              "id" => $id,
              "date" => $date,
              "product_id" => $product_id,
              "description" => $description,
              "price" => $price,
              "weight" => $weight,
              "quantity" => $quantity,
              "place_id" => $place_id,
              "payment_method_id" => $payment_method_id,
              "type_of_expense_id" => $type_of_expense_id,
              "comment" => $comment,
              "category_of_expense_id" => $category_of_expense_id
              ));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(Expense::class);
          $expenses = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $expenses));
        }

    }
}
