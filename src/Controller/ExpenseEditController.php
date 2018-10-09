<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;
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

      if(!isset($date) || empty($date)) {
        return $this->json(array('status' => 'error', 'message' => 'Wrong value of date or you don\'t provide date'));
      }
      if(!isset($product_id) || empty($product_id)) {
        return $this->json(array('status' => 'error', 'message' => 'Wrong value of product or you don\'t provide product'));
      }
      if(!isset($price) || empty($price)) {
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

      $expense = $this->getDoctrine()
      ->getRepository(Expense::class)
      ->find($id);
      if(!$expense) {
        return $this->json(array('status' => 'error', 'message' => 'Object not found'));
      } else {
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
          return $this->json(array('status' => 'error', 'message' => 'Cannot change Expense because new record already exists in database'));
        }

      }

    }
}
