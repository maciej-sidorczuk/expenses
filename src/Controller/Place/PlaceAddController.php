<?php

namespace App\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class PlaceAddController extends AbstractController
{
    /**
     * @Route("/purchase/place/add", name="purchase_place_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $name = trim($name);
          $name = ucwords($name);
          $place = new Place();
          $place->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($place);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $place->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Place is already in database.'));
          }
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
