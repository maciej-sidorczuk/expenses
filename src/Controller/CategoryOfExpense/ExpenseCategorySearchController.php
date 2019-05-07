<?php

namespace App\Controller\CategoryOfExpense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;

class ExpenseCategorySearchController extends AbstractController
{
    /**
     * @Route("/expensecategory/search", name="expensecategory_search")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $query_string = 'SELECT p FROM App\Entity\CategoryOfExpense p WHERE p.name like \'%' . $name . '%\'';
          $expensecategories = $this->getDoctrine()
            ->getRepository(CategoryOfExpense::class)
            ->searchByString($query_string);
          return $this->json(array('status' => 'ok', 'content' => $expensecategories));
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Please provide product\'s name'));
        }
    }
}
