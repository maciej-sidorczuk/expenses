<?php

namespace App\Controller\PaymentMethod;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PaymentMethod;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class PaymentMethodAddController extends AbstractController
{
    /**
     * @Route("/payment/add", name="payment_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $name = trim($name);
          $name = ucwords($name);
          $paymentMethod = new PaymentMethod();
          $paymentMethod->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($paymentMethod);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $paymentMethod->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Payment method is already in database.'));
          }
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
