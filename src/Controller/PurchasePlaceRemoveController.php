<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Place;
use Doctrine\DBAL\DBALException;

class PurchasePlaceRemoveController extends AbstractController
{
    /**
     * @Route("/purchase/place/remove", name="purchase_place_remove")
     */
    public function index(Request $request)
    {
        $id = $request->request->get('id');
        if(isset($id) && !empty($id)) {
          if(is_array($id)) {
            try {
              $place = $this->getDoctrine()
              ->getRepository(Place::class)
              ->deleteAll($id);
            } catch(DBALException $e) {
              return $this->json(array('status' => 'error', 'message' => 'Cannot remove purchase place because there are expenses which contain this place. Consider remove these expenses/keep this purchase place for historic purpose/edit purchase product for new one'));
            }
            return $this->json(array('status' => 'ok'));
          } else {
            $place = $this->getDoctrine()
            ->getRepository(Place::class)
            ->find($id);
            if(!$place) {
              return $this->json(array('status' => 'error', 'message' => 'Object not found'));
            } else {
              $entityManager = $this->getDoctrine()->getManager();
              try {
                $entityManager->remove($place);
                $entityManager->flush();
                return $this->json(array('status' => 'ok'));
              } catch(DBALException $e) {
                return $this->json(array('status' => 'error', 'message' => 'Cannot remove purchase place because there are expenses which contain this place. Consider remove these expenses/keep this purchase place for historic purpose/edit purchase product for new one'));
              }

            }
          }
        } else {
          return $this->json(array('status' => 'error', 'message' => 'Incorrect parameters'));
        }
    }
}
