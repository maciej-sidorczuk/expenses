<?php

namespace App\Controller\ExpenseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;
use Doctrine\DBAL\DBALException;

class ExpenseTypeRemoveController extends AbstractController
{
    /**
     * @Route("/expensetype/remove", name="expensetype_remove")
     */
     public function index(Request $request)
     {
         $id = $request->request->get('id');
         if(isset($id) && !empty($id)) {
           if(is_array($id)) {
             try {
               $typeOfExpense = $this->getDoctrine()
               ->getRepository(TypeOfExpense::class)
               ->deleteAll($id);
             } catch(DBALException $e) {
               return $this->json(array('status' => 'error', 'message' => 'Cannot remove expense type because there are expenses which contain this type. Consider remove these expenses/keep this type for historic purpose/edit expense type for new one'));
             }
             return $this->json(array('status' => 'ok'));
           } else {
             $typeOfExpense = $this->getDoctrine()
             ->getRepository(TypeOfExpense::class)
             ->find($id);
             if(!$typeOfExpense) {
               return $this->json(array('status' => 'error', 'message' => 'Object not found'));
             } else {
               $entityManager = $this->getDoctrine()->getManager();
               try {
                 $entityManager->remove($typeOfExpense);
                 $entityManager->flush();
                 return $this->json(array('status' => 'ok'));
               } catch(DBALException $e) {
                 return $this->json(array('status' => 'error', 'message' => 'Cannot remove expense type because there are expenses which contain this type. Consider remove these expenses/keep this type for historic purpose/edit expense type for new one'));
               }

             }
           }
         } else {
           return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
         }
     }

}
