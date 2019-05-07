<?php

namespace App\Controller\ExpenseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TypeOfExpense;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class ExpenseTypeFormController extends AbstractController
{
    /**
     * @Route("/typeofexpense/create", name="type_of_expense_form_add")
     */
    public function create(Request $request)
    {

        $typeOfExpense = new TypeOfExpense();
        $form = $this->createFormBuilder($typeOfExpense)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Create Type Of Expense"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $typeOfExpense = $form->getData();
          $entityManager->persist($typeOfExpense);
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('expensetype_view');

        }

        return $this->render('ExpenseType/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/typeofexpense/modify/{id}", name="type_of_expense_form_edit")
     */
    public function modify(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $typeOfExpense = $entityManager
        ->getRepository(TypeOfExpense::class)
        ->find($id);

        $form = $this->createFormBuilder($typeOfExpense)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Edit Type Of Expense"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('expensetype_view');
        }

        return $this->render('ExpenseType/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
