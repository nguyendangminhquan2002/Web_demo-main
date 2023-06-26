<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Form\ProductType;

class ProductController extends AbstractController
{
     /**
    * @Route("/product",name="product_index")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
    * @Route("/product/create", name="product_create", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }
        return $this->render('product/create.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/product/edit/{id}", name="product_edit", methods={"GET","POST"})
    */
    public function editAction(Request $request, $id)
   {
       $em = $this->getDoctrine()->getManager();
       $product = $em->getRepository(Product::class)->find($id);
       
       $editForm = $this->createForm(ProductType::class, $product);
       
       $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $em = $this->getDoctrine()->getManager()->flush();
           return $this->redirectToRoute('product_show', array('id' => $id));
       }
       
       return $this->render('product/edit.html.twig', [
           'id' => $id,
           'edit_form' => $editForm->createView()
       ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $em->remove($product);
        $em->flush();
        
        $this->addFlash(
            'error',
            'Product deleted'
        );
        
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public
    function showAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    
}