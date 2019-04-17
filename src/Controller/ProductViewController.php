<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductViewController extends AbstractController
{
    /**
     * @Route("/product/", name="product_view")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        //file_put_contents('/var/www/mswydatki.pl/prodlog.log', print_r($products, true) . "\n", FILE_APPEND);
        return $this->render('product/view.html.twig', array('products' => $products));
    }


}
