<?php

namespace App\Controller\ExpenseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;

class ExpenseTypeShowController extends AbstractController
{
    /**
     * @Route("/expensetype/show", name="expensetype_show")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $typeOfExpense = $this->getDoctrine()
          ->getRepository(TypeOfExpense::class)
          ->find($id);
          if(!$typeOfExpense) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $id = $typeOfExpense->getId();
            $name = $typeOfExpense->getName();
            $objects = array();
            array_push($objects, array("id" => $id, "name" => $name));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(TypeOfExpense::class);
          $typeOfExpenses = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $typeOfExpenses));
        }
    }

    /**
     * @Route("/expensetype/showbyname", name="expensetype_showbyname")
     */
     public function showByName(Request $request) {
       $name = $request->request->get('name');
       if(isset($name) && !empty($name)) {
         $name = trim($name);
         $name = ucwords($name);
         $query_string = 'SELECT p FROM App\Entity\TypeOfExpense p WHERE p.name = :name';
         $typeOfExpenses = $this->getDoctrine()
           ->getRepository(TypeOfExpense::class)
           ->searchByString($query_string, $name);
         return $this->json(array('status' => 'ok', 'content' => $typeOfExpenses));
       } else {
         return $this->json(array('status' => 'error', 'message' => 'You didn\'t provide type of expense'));
       }
     }
}
