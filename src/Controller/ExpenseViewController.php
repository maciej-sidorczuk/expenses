<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseViewController extends AbstractController {

  /**
   * @Route("/expense/", name="expense_view")
   */
   function index() {
     return $this->render('expense/view.html.twig');
   }
   


}




 ?>
