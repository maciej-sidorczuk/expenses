<?php

namespace App\Controller\Expense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Product\ProductShowController;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\ExpenseType\ExpenseTypeShowController;
use App\Controller\PaymentMethod\PaymentMethodShowController;
use App\Controller\CategoryOfExpense\ExpenseCategoryShowController;
use App\Controller\Place\PlaceShowController;

class ExpenseViewController extends AbstractController {

  /**
   * @Route("/expense/", name="expense_view")
   */
   function index(
     ProductShowController $productShowController,
     ExpenseTypeShowController $expenseTypeController,
     PaymentMethodShowController $paymentShowController,
     ExpenseCategoryShowController $expensecategoryShowController,
     PlaceShowController $purchasePlaceShowController
   ) {
     $request = Request::createFromGlobals();
     $response_products = $productShowController->index($request);
     $response_expenseType = $expenseTypeController->index($request);
     $response_paymentMethod = $paymentShowController->index($request);
     $response_expenseCategory = $expensecategoryShowController->index($request);
     $response_purchasePlace = $purchasePlaceShowController->index($request);

     $json_products = $response_products->getContent();
     $json_expenseType = $response_expenseType->getContent();
     $json_paymentMethod = $response_paymentMethod->getContent();
     $json_expenseCategory = $response_expenseCategory->getContent();
     $json_purchasePlace= $response_purchasePlace->getContent();
     $product_array = json_decode($json_products)->content;
     $expenseType_array = json_decode($json_expenseType)->content;
     $paymentMethod_array = json_decode($json_paymentMethod )->content;
     $expenseCategory_array = json_decode($json_expenseCategory)->content;
     $purchasePlace_array = json_decode($json_purchasePlace)->content;

     return $this->render('Expense/view.html.twig', array(
       'products' => $product_array,
       'expenseType' => $expenseType_array,
       'paymentMethod' => $paymentMethod_array,
       'expenseCategory' => $expenseCategory_array,
       'purchasePlace' => $purchasePlace_array
     ));
   }



}




 ?>
