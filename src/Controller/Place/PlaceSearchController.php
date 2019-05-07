<?php

namespace App\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PlaceSearchController extends AbstractController
{
    /**
     * @Route("/place/search", name="place_search")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $query_string = 'SELECT p FROM App\Entity\Place p WHERE p.name like \'%' . $name . '%\'';
          $places = $this->getDoctrine()
            ->getRepository(Place::class)
            ->searchByString($query_string);
          return $this->json(array('status' => 'ok', 'content' => $places));
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Please provide place'));
        }
    }
}
