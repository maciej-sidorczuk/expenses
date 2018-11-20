<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\ProductShowController;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\ExpensetypeShowController;
use App\Controller\PaymentShowController;
use App\Controller\ExpensecategoryShowController;
use App\Controller\PurchasePlaceShowController;

class ExpenseViewController extends AbstractController {

  /**
   * @Route("/expense/", name="expense_view")
   */
   function index(
     ProductShowController $productShowController,
     ExpensetypeShowController $expenseTypeController,
     PaymentShowController $paymentShowController,
     ExpensecategoryShowController $expensecategoryShowController,
     PurchasePlaceShowController $purchasePlaceShowController
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

     return $this->render('expense/view.html.twig', array(
       'products' => $product_array,
       'expenseType' => $expenseType_array,
       'paymentMethod' => $paymentMethod_array,
       'expenseCategory' => $expenseCategory_array,
       'purchasePlace' => $purchasePlace_array
     ));
   }



}




 ?>
