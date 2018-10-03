<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;

class PaymentRemoveController extends AbstractController
{
    /**
     * @Route("/payment/remove", name="payment_remove")
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paymentMethod);
            $entityManager->flush();
            return $this->json(array('status' => 'ok'));
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
