<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PurchasePlaceViewController extends AbstractController
{
    /**
     * @Route("/place/", name="place_view")
     */
    public function index(Request $request)
    {
        return $this->render('place/view.html.twig');
    }


}
