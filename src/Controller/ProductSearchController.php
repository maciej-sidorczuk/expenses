<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductSearchController extends AbstractController
{
    /**
     * @Route("/product/search", name="product_search")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $query_string = 'SELECT p FROM App\Entity\Product p WHERE p.name like \'%' . $name . '%\'';
          $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->searchByString($query_string);
          return $this->json(array('status' => 'ok', 'content' => $products));
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Please provide product\'s name'));
        }
    }
}
