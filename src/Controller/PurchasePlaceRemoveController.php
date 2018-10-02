<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PurchasePlaceRemoveController extends AbstractController
{
    /**
     * @Route("/purchase/place/remove", name="purchase_place_remove")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $place = $this->getDoctrine()
          ->getRepository(Place::class)
          ->find($id);
          if(!$place) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($place);
            $entityManager->flush();
            return $this->json(array('status' => 'ok'));
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
