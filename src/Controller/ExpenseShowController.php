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
            array_push($objects, array("0" => array(
              "id" => $id,
              "purchaseDate" => $date,
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
              ), "total_price" => $quantity*$price));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $where = '';
          $having = '';
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
            $price_min = str_replace(",", ".", $price_min);
            if(is_numeric($price_min)) {
              $where .= 'p.price >= :price_min AND ';
              $values_to_add['price_min'] = $price_min;
            }
          }

          $total_price_min = $request->request->get('total_price_min');
          if(isset($total_price_min) && !empty($total_price_min)) {
            $total_price_min = str_replace(",", ".", $total_price_min);
            if(is_numeric($total_price_min)) {
              $having .= 'total_price >= :total_price_min AND ';
              $values_to_add['total_price_min'] = $total_price_min;
            }
          }

          $total_price_max = $request->request->get('total_price_max');
          if(isset($total_price_max) && !empty($total_price_max)) {
            $total_price_max = str_replace(",", ".", $total_price_max);
            if(is_numeric($total_price_max)) {
              $having .= 'total_price <= :total_price_max AND ';
              $values_to_add['total_price_max'] = $total_price_max;
            }
          }

          $price_max = $request->request->get('price_max');
          if(isset($price_max) && !empty($price_max)) {
            $price_max = str_replace(",", ".", $price_max);
            if(is_numeric($price_max)) {
              $where .= 'p.price <= :price_max AND ';
              $values_to_add['price_max'] = $price_max;
            }
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
            $weight_min = str_replace(",", ".", $weight_min);
            if(is_numeric($weight_min)) {
              $where .= 'p.weight >= :weight_min AND ';
              $values_to_add['weight_min'] = $weight_min;
            }
          }

          $weight_max = $request->request->get('weight_max');
          if(isset($weight_max) && !empty($weight_max)) {
            $weight_max = str_replace(",", ".", $weight_max);
            if(is_numeric($weight_max)) {
              $where .= 'p.weight <= :weight_max AND ';
              $values_to_add['weight_max'] = $weight_max;
            }
          }

          $places = $request->request->get('place');
          if(isset($places) && !empty($places)) {
            $places_string = implode(',', $places);
            $where .= 'p.place_id IN(' . $places_string . ') AND ';
          }

          $payments = $request->request->get('payment');
          if(isset($payments) && !empty($payments)) {
            $payments_string = implode(',', $payments);
            $where .= 'p.payment_method_id IN(' . $payments_string . ') AND ';
          }

          $products = $request->request->get('product');
          if(isset($products) && !empty($products)) {
            $products_string = implode(',', $products);
            $where .= 'p.product_id IN(' . $products_string . ') AND ';
          }

          $type_of_expenses = $request->request->get('expense_type');
          if(isset($type_of_expenses) && !empty($type_of_expenses)) {
            $type_of_expenses_string = implode(',', $type_of_expenses);
            $where .= 'p.type_of_expense_id IN(' . $type_of_expenses_string  . ') AND ';
          }

          $category_of_expenses = $request->request->get('expense_category');
          if(isset($category_of_expenses) && !empty($category_of_expenses)) {
            $category_of_expenses_string = implode(',', $category_of_expenses);
            $where .= 'p.category_of_expense_id IN(' . $category_of_expenses_string  . ') AND ';
          }

          $description = $request->request->get('description');
          if(isset($description) && !empty($description)) {
            $where .= 'p.description like \'%' . $description  . '%\' AND ';
          }

          $comment = $request->request->get('comment');
          if(isset($comment) && !empty($comment)) {
            $where .= 'p.comment like \'%' . $comment  . '%\' AND ';
          }


          if($where == '') {
            if($having == '') {
              $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p';
              $expenses = $this->getDoctrine()
                ->getRepository(Expense::class)
                ->searchAll($query_string);
              return $this->json(array('status' => 'ok', 'content' => $expenses));
            } else {
              $having = trim($having);
              $having = preg_replace("/\sAND$/", '', $having);
              $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p HAVING ' . $having ;
              $expenses = $this->getDoctrine()
                ->getRepository(Expense::class)
                ->searchAll($query_string, $values_to_add);
              return $this->json(array('status' => 'ok', 'content' => $expenses));
            }
          } else {
            $where = trim($where);
            $where = preg_replace("/\sAND$/", '', $where);
            $where = ' WHERE ' . $where;
            $having = trim($having);
            $having = preg_replace("/\sAND$/", '', $having);
            if($having != '') {
              $having = ' HAVING ' . $having;
            }
            $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p' . $where . $having;
            $expenses = $this->getDoctrine()
              ->getRepository(Expense::class)
              ->searchByParams($query_string, $values_to_add, $limit);
            return $this->json(array('status' => 'ok', 'content' => $expenses));
          }


        }

    }
}
