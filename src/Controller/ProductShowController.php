<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductShowController extends AbstractController
{
    /**
     * @Route("/product/show", name="product_show")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          $Product = $this->getDoctrine()
          ->getRepository(Product::class)
          ->find($id);
          if(!$Product) {
            return $this->json(array('status' => 'error', 'message' => 'Object not found'));
          } else {
            $id = $Product->getId();
            $name = $Product->getName();
            $objects = array();
            array_push($objects, array("id" => $id, "name" => $name));
            return $this->json(array('status' => 'ok', 'content' => $objects));
          }
        } else {
          $repository = $this->getDoctrine()->getRepository(Product::class);
          $Products = $repository->findAll();
          return $this->json(array('status' => 'ok', 'content' => $Products));
        }
    }
}
