<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;

class ExpensecategoryShowController extends AbstractController
{
    /**
     * @Route("/expensecategory/show", name="expensecategory_show")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $categoryOfExpense = $this->getDoctrine()
          ->getRepository(CategoryOfExpense::class)
          ->find($id);
          if(!$categoryOfExpense) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $id = $categoryOfExpense->getId();
            $name = $categoryOfExpense->getName();
            $objects = array();
            array_push($objects, array("id" => $id, "name" => $name));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(CategoryOfExpense::class);
          $categoryOfExpenses = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $categoryOfExpenses));
        }
    }

    /**
     * @Route("/expensecategory/showbyname", name="expensecategory_showbyname")
     */
     public function showByName(Request $request) {
       $name = $request->request->get('name');
       if(isset($name) && !empty($name)) {
         $name = trim($name);
         $name = ucwords($name);
         $query_string = 'SELECT p FROM App\Entity\CategoryOfExpense p WHERE p.name = :name';
         $cats = $this->getDoctrine()
           ->getRepository(CategoryOfExpense::class)
           ->searchByString($query_string, $name);
         return $this->json(array('status' => 'ok', 'content' => $cats));
       } else {
         return $this->json(array('status' => 'error', 'message' => 'You didn\'t provide expense category name'));
       }
     }
}
