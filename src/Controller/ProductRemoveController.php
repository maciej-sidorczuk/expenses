<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;

class ProductRemoveController extends AbstractController
{
    /**
     * @Route("/product/remove", name="product_remove")
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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Product);
            $entityManager->flush();
            return $this->json(array('status' => 'ok'));
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
