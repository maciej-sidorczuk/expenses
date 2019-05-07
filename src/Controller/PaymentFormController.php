<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PaymentMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class PaymentFormController extends AbstractController
{
    /**
     * @Route("/payment/create", name="payment_methods_form_add")
     */
    public function create(Request $request)
    {

        $paymentMethod = new PaymentMethod();
        $form = $this->createFormBuilder($paymentMethod)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Create Payment Method"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $paymentMethod = $form->getData();
          $entityManager->persist($paymentMethod);
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('payment_view');

        }

        return $this->render('paymentmethod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/payment/modify/{id}", name="payment_methods_form_edit")
     */
    public function modify(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $typeOfExpense = $entityManager
        ->getRepository(PaymentMethod::class)
        ->find($id);

        $form = $this->createFormBuilder($typeOfExpense)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Edit Payment Method"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('payment_view');
        }

        return $this->render('paymentmethod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
