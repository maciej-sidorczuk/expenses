<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Place;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class PurchasePlaceController extends AbstractController
{
    /**
     * @Route("/purchase/place", name="purchase_place")
     */
    public function index(Request $request)
    {

        $place = new Place();
        $form = $this->createFormBuilder($place)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => 'Create Place'))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $place = $form->getData();
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($place);
          $entityManager->flush();
          return new Response(
            '<html><body>Place was added.</body></html>'
          );
        }


        return $this->render('purchase_place/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
