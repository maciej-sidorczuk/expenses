<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class PaymentEditController extends AbstractController
{
    /**
     * @Route("/payment/edit", name="payment_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $name = $request->request->get('name');

      if(isset($name) && !empty($name) && isset($id) && !empty($id)) {
        $paymentMethod = $this->getDoctrine()
        ->getRepository(PaymentMethod::class)
        ->find($id);
        if(!$paymentMethod) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          $name = trim($name);
          $name = ucwords($name);
          $paymentMethod->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($paymentMethod);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $paymentMethod->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Cannot change payment method because new record already exists in database'));
          }

        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
