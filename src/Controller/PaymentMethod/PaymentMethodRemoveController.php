<?php

namespace App\Controller\PaymentMethod;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;
use Doctrine\DBAL\DBALException;

class PaymentMethodRemoveController extends AbstractController
{
    /**
     * @Route("/payment/remove", name="payment_remove")
     */
     public function index(Request $request)
     {
         $id = $request->request->get('id');
         if(isset($id) && !empty($id)) {
           if(is_array($id)) {
             try {
               $paymentMethod = $this->getDoctrine()
               ->getRepository(PaymentMethod::class)
               ->deleteAll($id);
             } catch(DBALException $e) {
               return $this->json(array('status' => 'error', 'message' => 'Cannot remove payment method because there are expenses which contain this method. Consider remove these expenses/keep this method for historic purpose/edit payment method for new one'));
             }
             return $this->json(array('status' => 'ok'));
           } else {
             $paymentMethod = $this->getDoctrine()
             ->getRepository(PaymentMethod::class)
             ->find($id);
             if(!$paymentMethod) {
               return $this->json(array('status' => 'error', 'message' => 'Object not found'));
             } else {
               $entityManager = $this->getDoctrine()->getManager();
               try {
                 $entityManager->remove($paymentMethod);
                 $entityManager->flush();
                 return $this->json(array('status' => 'ok'));
               } catch(DBALException $e) {
                 return $this->json(array('status' => 'error', 'message' => 'Cannot remove payment method because there are expenses which contain this method. Consider remove these expenses/keep this method for historic purpose/edit payment method for new one'));
               }

             }
           }
         } else {
           return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
         }
     }

}
