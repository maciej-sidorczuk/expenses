<?php

namespace App\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class ProductFormController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_form_add")
     */
    public function create(Request $request)
    {

        $product = new Product();
        $form = $this->createFormBuilder($product)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Create Product"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          $product = $form->getData();
          $entityManager->persist($product);
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('product_view');

        }

        return $this->render('Product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/modify/{id}", name="product_form_edit")
     */
    public function modify(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager
        ->getRepository(Product::class)
        ->find($id);

        $form = $this->createFormBuilder($product)
          ->add('name', TextType::class)
          ->add('save', SubmitType::class, array('label' => "Edit Product"))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();
          //TODO add flash message
          return $this->redirectToRoute('product_view');
        }

        return $this->render('Product/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
