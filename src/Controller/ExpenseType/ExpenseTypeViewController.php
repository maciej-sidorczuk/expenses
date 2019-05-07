<?php

namespace App\Controller\ExpenseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;

class ExpenseTypeViewController extends AbstractController
{
    /**
     * @Route("/expensetype/", name="expensetype_view")
     */
    public function index(Request $request)
    {
      $repository = $this->getDoctrine()->getRepository(TypeOfExpense::class);
      $typesOfExpense = $repository->findAll();
      return $this->render('ExpenseType/view.html.twig', array('typesofexpense' => $typesOfExpense));
    }


}
