<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;

class ExpensetypeViewController extends AbstractController
{
    /**
     * @Route("/expensetype/", name="expensetype_view")
     */
    public function index(Request $request)
    {
      $repository = $this->getDoctrine()->getRepository(TypeOfExpense::class);
      $typesOfExpense = $repository->findAll();
      return $this->render('typeofexpense/view.html.twig', array('typesofexpense' => $typesOfExpense));
    }


}
