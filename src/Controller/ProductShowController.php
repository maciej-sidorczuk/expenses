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

    /**
     * @Route("/product/showbyname", name="product_showbyname")
     */
     public function showByName(Request $request) {
       $name = $request->request->get('name');
       if(isset($name) && !empty($name)) {
         $name = trim($name);
         $name = ucwords($name);
         $query_string = 'SELECT p FROM App\Entity\Product p WHERE p.name = :name';
         $products = $this->getDoctrine()
           ->getRepository(Product::class)
           ->searchByString($query_string, $name);
         return $this->json(array('status' => 'ok', 'content' => $products));
       } else {
         return $this->json(array('status' => 'error', 'message' => 'You didn\'t provide product name'));
       }
     }

}
