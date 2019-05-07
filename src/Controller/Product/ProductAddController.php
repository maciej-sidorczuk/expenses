<?php

namespace App\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class ProductAddController extends AbstractController
{
    /**
     * @Route("/product/add", name="product_add")
     */
    public function index(Request $request)
    {
        $name = $request->request->get('name');
        if(isset($name) && !empty($name)) {
          $name = trim($name);
          $name = ucwords($name);
          $Product = new Product();
          $Product->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($Product);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $Product->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Product is already in database.'));
          }
        } else {
          return $this->json(array('status' => 'error'));
        }
    }
}
