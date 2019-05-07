<?php

namespace App\Controller\Expense;

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


class ExpenseAddController extends AbstractController
{
    /**
     * @Route("/expense/add", name="expense_add")
     */
    public function index(Request $request)
    {
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

        if(!isset($date) || empty($date)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of date or you don\'t provide date'));
        }
        if(!isset($product_id) || empty($product_id)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of product or you don\'t provide product'));
        }
        if(!isset($price) || $price == "") {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of price or you don\'t provide price'));
        }
        if(!isset($quantity) || empty($quantity)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of quantity or you don\'t provide quantity'));
        }
        if(!isset($place_id) || empty($place_id)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of place or you don\'t provide place'));
        }
        if(!isset($payment_method_id) || empty($payment_method_id)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of payment or you don\'t provide payment'));
        }
        if(!isset($type_of_expense_id) || empty($type_of_expense_id)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong value of expense or you don\'t provide type of expense'));
        }
        if(!isset($category_of_expense_id) || empty($category_of_expense_id)) {
          return $this->json(array('status' => 'error', 'message' => 'Wrong expense\'s category value or you don\'t provide category of expense'));
        }
        if(!isset($weight) || $weight == "") {
          $weight = null;
        } else {
          $weight = str_replace(",", ".", $weight);
          if(!is_numeric($weight)) {
            return $this->json(array('status' => 'error', 'message' => 'Weight is not a number value!'));
          }
        }
        $quantity_string = $quantity;
        $quantity = intval($quantity);
        if(!is_int($quantity) || $quantity_string != (string) $quantity || $quantity < 1) {
          return $this->json(array('status' => 'error', 'message' => 'Quantity is not a integer number or it is less than 0!'));
        }
        $price = str_replace(",", ".", $price);
        if(!is_numeric($price)) {
          return $this->json(array('status' => 'error', 'message' => 'Price is not a float number!'));
        }
        $price = (float) $price;
        if(!is_float($price)) {
          return $this->json(array('status' => 'error', 'message' => 'Price is not a float number!'));
        }
        $expense = new Expense();
        $product = $this->getDoctrine()
          ->getRepository(Product::class)
          ->find($product_id);
        if(!$product) {
          return $this->json(array('status' => 'error', 'message' => 'Selected product does not exist'));
        }
        $place =$this->getDoctrine()
          ->getRepository(Place::class)
          ->find($place_id);
        if(!$place) {
          return $this->json(array('status' => 'error', 'message' => 'Selected place does not exist'));
        }
        $payment = $this->getDoctrine()
          ->getRepository(PaymentMethod::class)
          ->find($payment_method_id);
        if(!$payment) {
          return $this->json(array('status' => 'error', 'message' => 'Selected payment method does not exist'));
        }
        $type_of_expense = $this->getDoctrine()
          ->getRepository(TypeOfExpense::class)
          ->find($type_of_expense_id);
        if(!$type_of_expense) {
          return $this->json(array('status' => 'error', 'message' => 'Selected type of expense does not exist'));
        }
        $category_of_expense = $this->getDoctrine()
          ->getRepository(CategoryOfExpense::class)
          ->find($category_of_expense_id);
        if(!$category_of_expense) {
          return $this->json(array('status' => 'error', 'message' => 'Selected category of expense does not exist'));
        }
        $expense->setPurchaseDate(new \DateTime($date));
        $expense->setProductId($product);
        $expense->setDescription($description);
        $expense->setPrice($price);
        $expense->setWeight($weight);
        $expense->setQuantity($quantity);
        $expense->setPlaceId($place);
        $expense->setPaymentMethodId($payment);
        $expense->setTypeOfExpenseId($type_of_expense);
        $expense->setComment($comment);
        $expense->setCategoryOfExpenseId($category_of_expense);

        $entityManager = $this->getDoctrine()->getManager();
        try {
          $entityManager->persist($expense);
          $entityManager->flush();
          return $this->json(array('status' => 'ok', 'id' => $expense->getId()));
        } catch (UniqueConstraintViolationException $e) {
          return $this->json(array('status' => 'error', 'message' => 'Something went wrong...'));
        }


    }
}
