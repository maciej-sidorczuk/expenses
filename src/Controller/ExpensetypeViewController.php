<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ExpensetypeViewController extends AbstractController
{
    /**
     * @Route("/expensetype/", name="expensetype_view")
     */
    public function index(Request $request)
    {
        
        return $this->render('typeofexpense/view.html.twig');
    }


}
