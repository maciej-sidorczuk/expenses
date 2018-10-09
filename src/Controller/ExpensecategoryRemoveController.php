<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CategoryOfExpense;
use Doctrine\DBAL\DBALException;

class ExpensecategoryRemoveController extends AbstractController
{
    /**
     * @Route("/expensecategory/remove", name="expensecategory_remove")
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
              $entityManager = $this->getDoctrine()->getManager();
            try {
              $entityManager->remove($categoryOfExpense);
              $entityManager->flush();
              return $this->json(array('status' => 'ok'));
            } catch(DBALException $e)) {
              return $this->json(array('status' => 'error', 'message' => 'Cannot remove expense category because there are expenses which contain this category. Consider remove these expenses/keep this category for historic purpose/edit expense category for new one'));
            }


          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
