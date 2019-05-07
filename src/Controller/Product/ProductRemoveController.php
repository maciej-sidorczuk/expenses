<?php

namespace App\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use Doctrine\DBAL\DBALException;

class ProductRemoveController extends AbstractController
{
    /**
     * @Route("/product/remove", name="product_remove")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          if(is_array($id)) {
            try {
              $product = $this->getDoctrine()
              ->getRepository(Product::class)
              ->deleteAll($id);
            } catch(DBALException $e) {
              return $this->json(array('status' => 'error', 'message' => 'Cannot remove product because there are expenses which contain this product. Consider remove these expenses/keep this product for historic purpose/edit product for new one'));
            }
            return $this->json(array('status' => 'ok'));
          } else {
            $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);
            if(!$product) {
              return $this->json(array('status' => 'error', 'message' => 'Object not found'));
            } else {
              $entityManager = $this->getDoctrine()->getManager();
              try {
                $entityManager->remove($product);
                $entityManager->flush();
                return $this->json(array('status' => 'ok'));
              } catch(DBALException $e) {
                return $this->json(array('status' => 'error', 'message' => 'Cannot remove product because there are expenses which contain this product. Consider remove these expenses/keep this product for historic purpose/edit product for new one'));
              }

            }
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
