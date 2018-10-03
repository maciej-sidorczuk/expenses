<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
          $name = trim($name);
          $name = ucwords($name);
          $place->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($place);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $place->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Cannot change place because new record already exists in database'));
          }

        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
