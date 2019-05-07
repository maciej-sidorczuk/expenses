<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;

class PurchasePlaceViewController extends AbstractController
{
    /**
     * @Route("/place/", name="place_view")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Place::class);
        $places = $repository->findAll();
        return $this->render('place/view.html.twig', array('places' => $places));
    }


}
