<?php

namespace App\Controller\PaymentMethod;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;

class PaymentMethodViewController extends AbstractController
{
    /**
     * @Route("/payment/", name="payment_view")
     */
     public function index(Request $request)
     {
       $repository = $this->getDoctrine()->getRepository(PaymentMethod::class);
       $payment = $repository->findAll();
       return $this->render('PaymentMethod/view.html.twig', array('paymentmethods' => $payment));
     }

}
