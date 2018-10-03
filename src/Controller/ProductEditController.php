<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ProductEditController extends AbstractController
{
    /**
     * @Route("/product/edit", name="product_edit")
     */
    public function index(Request $request)
    {
      $id = $request->request->get('id');
      $name = $request->request->get('name');

      if(isset($name) && !empty($name) && isset($id) && !empty($id)) {
        $Product = $this->getDoctrine()
        ->getRepository(Product::class)
        ->find($id);
        if(!$Product) {
          return $this->json(array('status' => 'error', 'message' => 'Object not found'));
        } else {
          $name = trim($name);
          $name = ucwords($name);
          $Product->setName($name);
          $entityManager = $this->getDoctrine()->getManager();
          try {
            $entityManager->persist($Product);
            $entityManager->flush();
            return $this->json(array('status' => 'ok', 'id' => $Product->getId()));
          } catch (UniqueConstraintViolationException $e) {
            return $this->json(array('status' => 'error', 'message' => 'Cannot change Product because new record already exists in database'));
          }

        }
      } else {
        return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
      }
    }
}
