<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PurchasePlaceEditController extends AbstractController
{
    /**
     * @Route("/purchase/place/edit", name="purchase_place_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $name = $request->request->get('name');

      if(isset($name) && !empty($name) && isset($id) && !empty($id)) {
        $place = $this->getDoctrine()
        ->getRepository(Place::class)
        ->find($id);
        if(!$place) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          $place->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($place);
          $entityManager->flush();
          return $this->json(array('status' => 'ok', 'id' => $place->getId()));
        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
