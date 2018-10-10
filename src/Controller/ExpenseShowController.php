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
          $where = '';
          $values_to_add = array();
          $from_date = $request->request->get('from_date');
          if(isset($from_date) && !empty($from_date)) {
            $where .= 'p.purchase_date >= :purchase_date_from AND ';
            $values_to_add['purchase_date_from'] = $from_date;
          }
          $to_date = $request->request->get('to_date');
          if(isset($to_date) && !empty($to_date)) {
            $where .= 'p.purchase_date <= :purchase_date_to AND ';
            $values_to_add['purchase_date_to'] = $to_date;
          }

          $limit = $request->request->get('limit');

          $price_min = $request->request->get('price_min');
          if(isset($price_min) && !empty($price_min)) {
            $where .= 'p.price >= :price_min AND ';
            $values_to_add['price_min'] = $price_min;
          }

          $price_max = $request->request->get('price_max');
          if(isset($price_max) && !empty($price_max)) {
            $where .= 'p.price <= :price_max AND ';
            $values_to_add['price_max'] = $price_max;
          }

          $quantity_min = $request->request->get('quantity_min');
          if(isset($quantity_min) && !empty($quantity_min)) {
            $where .= 'p.quantity >= :quantity_min AND ';
            $values_to_add['quantity_min'] = $quantity_min;
          }

          $quantity_max = $request->request->get('quantity_max');
          if(isset($quantity_max) && !empty($quantity_max)) {
            $where .= 'p.quantity <= :quantity_max AND ';
            $values_to_add['quantity_max'] = $quantity_max;
          }

          $weight_min = $request->request->get('weight_min');
          if(isset($weight_min) && !empty($weight_min)) {
            $where .= 'p.weight >= :weight_min AND ';
            $values_to_add['weight_min'] = $weight_min;
          }

          $weight_max = $request->request->get('weight_max');
          if(isset($weight_max) && !empty($weight_max)) {
            $where .= 'p.weight <= :weight_max AND ';
            $values_to_add['weight_max'] = $weight_max;
          }



          if($where == '') {
            $repository = $this->getDoctrine()->getRepository(Expense::class);
            $expenses = $repository->findAll();
            return $this->json(array('status' => 'ok', 'content' => $expenses));
          } else {
            $where = trim($where);
            $where = preg_replace("/\sAND$/", '', $where);
            $query_string = 'SELECT p FROM App\Entity\Expense p WHERE ' . $where;
            $expenses = $this->getDoctrine()
              ->getRepository(Expense::class)
              ->searchByParams($query_string, $values_to_add, $limit);
            return $this->json(array('status' => 'ok', 'content' => $expenses));
          }


        }

    }
}
