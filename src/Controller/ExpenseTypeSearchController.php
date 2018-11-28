<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;

class ExpenseTypeSearchController extends AbstractController
{
    /**
     * @Route("/expensetype/search", name="expensetype_search")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $query_string = 'SELECT p FROM App\Entity\TypeOfExpense p WHERE p.name like \'%' . $name . '%\'';
          $expensetypes = $this->getDoctrine()
            ->getRepository(TypeOfExpense::class)
            ->searchByString($query_string);
          return $this->json(array('status' => 'ok', 'content' => $expensetypes));
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Please provide expense type'));
        }
    }
}
