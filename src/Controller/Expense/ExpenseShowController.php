<?php

namespace App\Controller\Expense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;
use App\Entity\CategoryOfExpense;
use App\Entity\PaymentMethod;
use App\Entity\Place;
use App\Entity\Product;
use App\Entity\TypeOfExpense;
use Symfony\Component\HttpFoundation\Response;

class ExpenseShowController extends AbstractController
{
    /**
     * @Route("/expense/show", name="expense_show")
     */
    public function index(Request $request)
    {
        $data = $this->getDataFromDB($request);
        if(empty($data)) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          return $this->json(array('status' => $data['status'], 'content' => $data['content'], 'calculations' => $data['calculations'], 'timeinfo' => $data['timeinfo']));
        }
    }

    /**
     * @Route("/expense/download", name="expense_download")
     */
    public function downloadExpense(Request $request)
    {
        $data = $this->getDataFromDB($request);
        $fileContent = "l.p.;Purchase date;Product;Description;Weight;Price;Quantity;Total price;Type of expense;Payment method;Category of expense;Place;Comment" . "\n";
        $counter = 0;
        if(!empty($data)) {
          foreach($data['content'] as $content) {
            foreach($content as $obj) {
              if($obj instanceof Expense) {
                $counter++;
                $purchaseDate = $obj->getPurchaseDate();
                $productName = $obj->getProductId()->getName();
                $description = $obj->getDescription();
                $weight = $obj->getWeight();
                $price = $obj->getPrice();
                $quantity = $obj->getQuantity();
                $sum = $price * $quantity;
                $sum = $this->convertNumverValue($sum);
                $price = $this->convertNumverValue($price);
                $weight = $this->convertNumverValue($weight);
                $cat = $obj->getCategoryOfExpenseId()->getName();
                $typeOfExpense = $obj->getTypeOfExpenseId()->getName();
                $place = $obj->getPlaceId()->getName();
                $paymentMethod = $obj->getPaymentMethodId()->getName();
                $comment = $obj->getComment();
                $fileContent .= $counter . ";" . $purchaseDate->format("Y-m-d") . ";" . $productName . ";" . $description . ";" . $weight . ";" . $price . ";" . $quantity . ";" . $sum . ";" . $typeOfExpense . ";" . $paymentMethod . ";" . $cat . ";" . $place . ";" . $comment . "\n";
              }
            }
          }
        }
        $fileName = "expenses.csv";
        $response = new Response($fileContent);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set("Content-Disposition", "attachment; filename=\"" . $fileName ."\"");
        return $response;
    }

    private function convertNumverValue($numberValue) {
      return str_replace(".", ",", str_replace(",", "", number_format(floatval($numberValue), 2)));
    }

    private function makeCalculations($data) {
      $calculationResult = array();
      $calculationResult['total'] = 0;
      $calculationResult['categories'] = array();
      $calculationResult['typeofexpense'] = array();
      $calculationResult['place'] = array();
      $calculationResult['paymentmethod'] = array();
      foreach($data as $singleData) {
        foreach($singleData as $obj) {
          if($obj instanceof Expense) {
            $price = $obj->getPrice();
            $quantity = $obj->getQuantity();
            $sum = $price * $quantity;
            $cat = $obj->getCategoryOfExpenseId()->getName();
            $typeOfExpense = $obj->getTypeOfExpenseId()->getName();
            $place = $obj->getPlaceId()->getName();
            $paymentMethod = $obj->getPaymentMethodId()->getName();
            $calculationResult['total'] += $sum;
            if(!isset($calculationResult['categories'][$cat])) {
              $calculationResult['categories'][$cat] = 0;
            }
            $calculationResult['categories'][$cat] += $sum;
            if(!isset($calculationResult['typeofexpense'][$typeOfExpense])) {
              $calculationResult['typeofexpense'][$typeOfExpense] = 0;
            }
            $calculationResult['typeofexpense'][$typeOfExpense] += $sum;
            if(!isset($calculationResult['place'][$place])) {
              $calculationResult['place'][$place] = 0;
            }
            $calculationResult['place'][$place] += $sum;
            if(!isset($calculationResult['paymentmethod'][$paymentMethod])) {
              $calculationResult['paymentmethod'][$paymentMethod] = 0;
            }
            $calculationResult['paymentmethod'][$paymentMethod] += $sum;
          }
        }
      }
      return $calculationResult;
    }
    private function getDataFromDB(Request $request) {
      $dataFromDB = array();
      $id = $request->request->get('id');
      if(isset($id) && !empty($id)) {
        $expense = $this->getDoctrine()
        ->getRepository(Expense::class)
        ->find($id);
        if(!$expense) {
          return $dataFromDB;
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
          $calculations = $this->makeCalculations($objects);
          $dataFromDB['status'] = 'ok';
          $dataFromDB['content'] = $objects;
          $dataFromDB['calculations'] = $calculations;
          return $dataFromDB;
        }
      } else {
        $where = '';
        $having = '';
        $values_to_add = array();
        $from_date = $request->request->get('from_date');
        if(isset($from_date) && !empty($from_date)) {
          $pattern = '/^\d{4}\-\d{2}\-\d{2}$/';
          if(preg_match($pattern, $from_date)) {
            $where .= 'p.purchase_date >= :purchase_date_from AND ';
            $values_to_add['purchase_date_from'] = $from_date;
          }
        }
        $to_date = $request->request->get('to_date');
        if(isset($to_date) && !empty($to_date)) {
          $pattern = '/^\d{4}\-\d{2}\-\d{2}$/';
          if(preg_match($pattern, $to_date)) {
            $where .= 'p.purchase_date <= :purchase_date_to AND ';
            $values_to_add['purchase_date_to'] = $to_date;
          }
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

        $order = $request->request->get('order');
        $order_direction = $request->request->get('order_direction');
        $order_sql = "";

        if(isset($order)) {
          switch($order) {
            case "product": $order_sql .= " ORDER BY " . "r.name"; break;
            case "typeofexpense": $order_sql .= " ORDER BY " . "s.name"; break;
            case "paymentmethod": $order_sql .= " ORDER BY " . "t.name"; break;
            case "categoryofexpense": $order_sql .= " ORDER BY " . "u.name"; break;
            case "place": $order_sql .= " ORDER BY " . "w.name"; break;
            case "totalprice" : $order_sql .= " ORDER BY " . "total_price"; break;
            default: $order_sql .= " ORDER BY " . "p." . $order;
          }
          if(isset($order_direction)) {
            if(strtoupper($order_direction) == "DESC") {
              $order_sql .= " DESC";
            } else {
              $order_sql .= " ASC";
            }
          } else {
            $order_sql .= " ASC";
          }
        }

        if($where == '') {
          if($having == '') {
            $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p' . $order_sql;
            if(isset($order)) {
              switch($order) {
                case "product": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Product r WITH p.product_id = r.id' . $order_sql; break;
                case "typeofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\TypeOfExpense s WITH p.type_of_expense_id = s.id' . $order_sql; break;
                case "paymentmethod": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\PaymentMethod t WITH p.payment_method_id = t.id' . $order_sql; break;
                case "categoryofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\CategoryOfExpense u WITH p.category_of_expense_id = u.id' . $order_sql; break;
                case "place": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Place w WITH p.place_id = w.id' . $order_sql; break;
                case "totalprice": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p ' . $order_sql; break;
              }
            }
            $expenses = $this->getDoctrine()
              ->getRepository(Expense::class)
              ->searchAll($query_string);
            $calculations = $this->makeCalculations($expenses);
            $timeInfo = "";
            if(isset($from_date)) {
              $timeInfo .= "from " . $from_date . " ";
            }
            if(isset($to_date)) {
              $timeInfo .= "to " . $to_date;
            }
            if(!isset($from_date) && !isset($to_date)) {
              $timeInfo = "all time";
            }
            $dataFromDB['status'] = 'ok';
            $dataFromDB['content'] = $expenses;
            $dataFromDB['calculations'] = $calculations;
            $dataFromDB['timeinfo'] = $timeInfo;
            return $dataFromDB;
          } else {
            $having = trim($having);
            $having = preg_replace("/\sAND$/", '', $having);
            $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p HAVING ' . $having . $order_sql;
            if(isset($order)) {
              switch($order) {
                case "product": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Product r WITH p.product_id = r.id HAVING ' . $having . $order_sql; break;
                case "typeofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\TypeOfExpense s WITH p.type_of_expense_id = s.id HAVING ' . $having . $order_sql; break;
                case "paymentmethod": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\PaymentMethod t WITH p.payment_method_id = t.id HAVING ' . $having . $order_sql; break;
                case "categoryofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\CategoryOfExpense u WITH p.category_of_expense_id = u.id HAVING ' . $having . $order_sql; break;
                case "place": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Place w WITH p.place_id = w.id HAVING ' . $having . $order_sql; break;
                case "totalprice": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p  HAVING ' . $having . $order_sql; break;
              }
            }
            $expenses = $this->getDoctrine()
              ->getRepository(Expense::class)
              ->searchAll($query_string, $values_to_add);
            $calculations = $this->makeCalculations($expenses);
            $timeInfo = "";
            if(isset($from_date)) {
              $timeInfo .= "from " . $from_date . " ";
            }
            if(isset($to_date)) {
              $timeInfo .= "to " . $to_date;
            }
            if(!isset($from_date) && !isset($to_date)) {
              $timeInfo .= "all time";
            }
            $dataFromDB['status'] = 'ok';
            $dataFromDB['content'] = $expenses;
            $dataFromDB['calculations'] = $calculations;
            $dataFromDB['timeinfo'] = $timeInfo;
            return $dataFromDB;
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
          $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p' . $where . $having . $order_sql;
          if(isset($order)) {
            switch($order) {
              case "product": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Product r WITH p.product_id = r.id' . $where . $having . $order_sql; break;
              case "typeofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\TypeOfExpense s WITH p.type_of_expense_id = s.id' . $where . $having . $order_sql; break;
              case "paymentmethod": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\PaymentMethod t WITH p.payment_method_id = t.id' . $where . $having . $order_sql; break;
              case "categoryofexpense": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\CategoryOfExpense u WITH p.category_of_expense_id = u.id' . $where . $having . $order_sql; break;
              case "place": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p INNER JOIN App\Entity\Place w WITH p.place_id = w.id' . $where . $having . $order_sql; break;
              case "totalprice": $query_string = 'SELECT p, p.quantity * p.price AS total_price FROM App\Entity\Expense p ' . $where . $having . $order_sql; break;
            }
          }
          $expenses = $this->getDoctrine()
            ->getRepository(Expense::class)
            ->searchByParams($query_string, $values_to_add, $limit);
          $calculations = $this->makeCalculations($expenses);
          $timeInfo = "";
          if(isset($from_date)) {
            $timeInfo .= "from " . $from_date . " ";
          }
          if(isset($to_date)) {
            $timeInfo .= "to " . $to_date;
          }
          if(!isset($from_date) && !isset($to_date)) {
            $timeInfo .= "all time";
          }
          $dataFromDB['status'] = 'ok';
          $dataFromDB['content'] = $expenses;
          $dataFromDB['calculations'] = $calculations;
          $dataFromDB['timeinfo'] = $timeInfo;
          return $dataFromDB;
        }
      }
    }
}
