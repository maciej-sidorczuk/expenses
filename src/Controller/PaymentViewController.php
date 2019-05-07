<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;

class PaymentViewController extends AbstractController
{
    /**
     * @Route("/payment/", name="payment_view")
     */
     public function index(Request $request)
     {
       $repository = $this->getDoctrine()->getRepository(PaymentMethod::class);
       $payment = $repository->findAll();
       return $this->render('paymentmethod/view.html.twig', array('paymentmethods' => $payment));
     }

}
