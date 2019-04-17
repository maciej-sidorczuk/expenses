<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ExpensecategoryViewController extends AbstractController
{
    /**
     * @Route("/expensecategory/", name="expensecategory_view")
     */
    public function index(Request $request)
    {
        return $this->render('categoryofexpense/view.html.twig');
    }


}
