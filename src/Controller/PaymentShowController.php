<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;

class PaymentShowController extends AbstractController
{
    /**
     * @Route("/payment/show", name="payment_show")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $paymentMethod = $this->getDoctrine()
          ->getRepository(PaymentMethod::class)
          ->find($id);
          if(!$paymentMethod) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $id = $paymentMethod->getId();
            $name = $paymentMethod->getName();
            $objects = array();
            array_push($objects, array("id" => $id, "name" => $name));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(PaymentMethod::class);
          $paymentMethods = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $paymentMethods));
        }
    }
}
