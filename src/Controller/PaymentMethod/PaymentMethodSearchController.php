<?php

namespace App\Controller\PaymentMethod;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;

class PaymentMethodSearchController extends AbstractController
{
    /**
     * @Route("/payment/search", name="payment_search")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $query_string = 'SELECT p FROM App\Entity\PaymentMethod p WHERE p.name like \'%' . $name . '%\'';
          $payments = $this->getDoctrine()
            ->getRepository(PaymentMethod::class)
            ->searchByString($query_string);
          return $this->json(array('status' => 'ok', 'content' => $payments));
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Please provide payment'));
        }
    }
}
