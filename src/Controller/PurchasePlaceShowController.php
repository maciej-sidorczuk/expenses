<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PurchasePlaceShowController extends AbstractController
{
    /**
     * @Route("/purchase/place/show", name="purchase_place_show")
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
            $id = $place->getId();
            $name = $place->getName();
            $objects = array();
            array_push($objects, array("id" => $id, "name" => $name));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(Place::class);
          $places = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $places));
        }
    }
}
