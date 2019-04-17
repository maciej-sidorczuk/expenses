<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentViewController extends AbstractController
{
    /**
     * @Route("/payment/", name="payment_view")
     */
    public function index(Request $request)
    {
        return $this->render('paymentmethod/view.html.twig');
    }


}
