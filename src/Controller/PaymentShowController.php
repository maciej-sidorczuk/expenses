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

    /**
     * @Route("/payment/showbyname", name="payment_showbyname")
     */
     public function showByName(Request $request) {
       $name = $request->request->get('name');
       if(isset($name) && !empty($name)) {
         $name = trim($name);
         $name = ucwords($name);
         $query_string = 'SELECT p FROM App\Entity\PaymentMethod p WHERE p.name = :name';
         $payments = $this->getDoctrine()
           ->getRepository(PaymentMethod::class)
           ->searchByString($query_string, $name);
         return $this->json(array('status' => 'ok', 'content' => $payments));
       } else {
         return $this->json(array('status' => 'error', 'message' => 'You didn\'t provide payment name'));
       }
     }
}
