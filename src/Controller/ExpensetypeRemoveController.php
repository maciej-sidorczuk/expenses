<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TypeOfExpense;

class ExpensetypeRemoveController extends AbstractController
{
    /**
     * @Route("/expensetype/remove", name="expensetype_remove")
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeOfExpense);
            $entityManager->flush();
            return $this->json(array('status' => 'ok'));
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
