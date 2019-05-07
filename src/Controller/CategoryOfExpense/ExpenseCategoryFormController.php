<?php

namespace App\Controller\CategoryOfExpense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CategoryOfExpense;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class ExpenseCategoryFormController extends AbstractController
{
    /**
     * @Route("/categoryofexpense/create", name="expense_categories_form_add")
     */
    public function create(Request $request)
    {

        $categoryOfExpense = new CategoryOfExpense();
        $form = $this->createFormBuilder($categoryOfExpense)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Create Category Of Expense"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $categoryOfExpense = $form->getData();
          $entityManager->persist($categoryOfExpense);
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('expensecategory_view');

        }

        return $this->render('CategoryOfExpense/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categoryofexpense/modify/{id}", name="expense_categories_form_edit")
     */
    public function modify(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryOfExpense = $entityManager
        ->getRepository(CategoryOfExpense::class)
        ->find($id);

        $form = $this->createFormBuilder($categoryOfExpense)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Edit Category Of Expense"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('expensecategory_view');
        }

        return $this->render('CategoryOfExpense/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
