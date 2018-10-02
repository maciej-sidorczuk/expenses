<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PurchasePlaceAddController extends AbstractController
{
    /**
     * @Route("/purchase/place/add", name="purchase_place_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $place = new Place();
          $place->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($place);
          $entityManager->flush();
          return $this->json(array('status' => 'ok', 'id' => $place->getId()));
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
