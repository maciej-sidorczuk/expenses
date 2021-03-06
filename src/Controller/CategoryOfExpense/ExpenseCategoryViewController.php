<?php

namespace App\Controller\CategoryOfExpense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;

class ExpenseCategoryViewController extends AbstractController
{
    /**
     * @Route("/expensecategory/", name="expensecategory_view")
     */
    public function index(Request $request)
    {
      $repository = $this->getDoctrine()->getRepository(CategoryOfExpense::class);
      $categoriesOfExpense = $repository->findAll();
      return $this->render('CategoryOfExpense/view.html.twig', array('categoriesofexpense' => $categoriesOfExpense));
    }


}
