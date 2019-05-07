<?php

namespace App\Controller\Place;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Place;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class PlaceFormController extends AbstractController
{
    /**
     * @Route("/place/create", name="place_form_add")
     */
    public function create(Request $request)
    {
        $place = new Place();
        $form = $this->createFormBuilder($place)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Create Place"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $place = $form->getData();
          $entityManager->persist($place);
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('place_view');

        }

        return $this->render('Place/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/place/modify/{id}", name="place_form_edit")
     */
    public function modify(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $place = $entityManager
        ->getRepository(Place::class)
        ->find($id);

        $form = $this->createFormBuilder($place)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Edit Place"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('place_view');
        }

        return $this->render('Place/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
