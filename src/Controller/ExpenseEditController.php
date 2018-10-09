<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;
use App\Entity\Product;
use App\Entity\CategoryOfExpense;
use App\Entity\Place;
use App\Entity\TypeOfExpense;
use App\Entity\PaymentMethod;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ExpenseEditController extends AbstractController
{
    /**
     * @Route("/expense/edit", name="expense_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $date = $request->request->get('date');
      $product_id = $request->request->get('product_id');
      $description = $request->request->get('description');
      $price = $request->request->get('price');
      $weight = $request->request->get('weight');
      $quantity = $request->request->get('quantity');
      $place_id = $request->request->get('place_id');
      $payment_method_id = $request->request->get('payment_method_id');
      $type_of_expense_id = $request->request->get('type_of_expense_id');
      $comment = $request->request->get('comment');
      $category_of_expense_id = $request->request->get('category_of_expense_id');

      $expense = $this->getDoctrine()
      ->getRepository(Expense::class)
      ->find($id);

      if(!$expense) {
        return $this->json(array('status' => 'error', 'message' => 'Object not found'));
      }

      if(isset($date) && $date != "") {
        $expense->setPurchaseDate(new \DateTime($date));
      }

      if(isset($product_id) && $product_id != "") {
        $product = $this->getDoctrine()
          ->getRepository(Product::class)
          ->find($product_id);
        if(!$product) {
          return $this->json(array('status' => 'error', 'message' => 'Selected product does not exist'));
        }
        $expense->setProductId($product);
      }

      if(isset($description) && $description != "") {
        $expense->setDescription($description);
      }

      if(isset($price) && $price != "") {
        $price = str_replace(",", ".", $price);
        if(!is_numeric($price)) {
          return $this->json(array('status' => 'error', 'message' => 'Price is not a float number!'));
        }
        $price = (float) $price;
        if(!is_float($price)) {
          return $this->json(array('status' => 'error', 'message' => 'Price is not a float number!'));
        }
        $expense->setPrice($price);
      }

      if(isset($weight) && $weight != "") {
        $weight = str_replace(",", ".", $weight);
        if(!is_numeric($weight)) {
          return $this->json(array('status' => 'error', 'message' => 'Weight is not a number value!'));
        }
        $expense->setWeight($weight);
      }

      if(isset($quantity) && $quantity != "") {
        $quantity_string = $quantity;
        $quantity = intval($quantity);
        if(!is_int($quantity) || $quantity_string != (string) $quantity || $quantity < 1) {
          return $this->json(array('status' => 'error', 'message' => 'Quantity is not a integer number or it is less than 0!'));
        }
        $expense->setQuantity($quantity);
      }

      if(isset($place_id) && $place_id != "") {
        $place =$this->getDoctrine()
          ->getRepository(Place::class)
          ->find($place_id);
        if(!$place) {
          return $this->json(array('status' => 'error', 'message' => 'Selected place does not exist'));
        }
        $expense->setPlaceId($place);
      }

      if(isset($payment_method_id) && $payment_method_id  != "") {
        $payment = $this->getDoctrine()
          ->getRepository(PaymentMethod::class)
          ->find($payment_method_id);
        if(!$payment) {
          return $this->json(array('status' => 'error', 'message' => 'Selected payment method does not exist'));
        }
        $expense->setPaymentMethodId($payment);
      }

      if(isset($type_of_expense_id) && $type_of_expense_id  != "") {
        $type_of_expense = $this->getDoctrine()
          ->getRepository(TypeOfExpense::class)
          ->find($type_of_expense_id);
        if(!$type_of_expense) {
          return $this->json(array('status' => 'error', 'message' => 'Selected type of expense does not exist'));
        }
        $expense->setTypeOfExpenseId($type_of_expense);
      }

      if(isset($comment) && $comment  != "") {
        $expense->setComment($comment);
      }

      if(isset($category_of_expense_id) && $category_of_expense_id  != "") {
        $category_of_expense = $this->getDoctrine()
          ->getRepository(CategoryOfExpense::class)
          ->find($category_of_expense_id);
        if(!$category_of_expense) {
          return $this->json(array('status' => 'error', 'message' => 'Selected category of expense does not exist'));
        }
        $expense->setCategoryOfExpenseId($category_of_expense);
      }

      $entityManager = $this->getDoctrine()->getManager();
      try {
        $entityManager->persist($expense);
        $entityManager->flush();
        return $this->json(array('status' => 'ok', 'id' => $expense->getId()));
      } catch (UniqueConstraintViolationException $e) {
        return $this->json(array('status' => 'error', 'message' => 'Cannot change Expense because new record already exists in database'));
      }

    }
}
