<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Expense;

class ExpenseRemoveController extends AbstractController
{
    /**
     * @Route("/expense/remove", name="expense_remove")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          if(is_array($id)) {
            $expense = $this->getDoctrine()
            ->getRepository(Expense::class)
            ->deleteAll($id);
            return $this->json(array('status' => 'ok'));
          } else {
            $expense= $this->getDoctrine()
            ->getRepository(Expense::class)
            ->find($id);
            if(!$expense) {
              return $this->json(array('status' => 'error', 'message' => 'Object not found'));
            } else {
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->remove($expense);
              $entityManager->flush();
              return $this->json(array('status' => 'ok'));
            }
          }

        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
