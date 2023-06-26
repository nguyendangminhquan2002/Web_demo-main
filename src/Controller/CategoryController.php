<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
     /**
    * @Route("/category",name="category_index")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }


    /**
    * @Route("/category/create", name="category_create", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }
        return $this->render('category/create.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/category/edit/{id}", name="category_edit", methods={"GET","POST"})
    */
    public function editAction(Request $request, $id)
   {
       $em = $this->getDoctrine()->getManager();
       $category = $em->getRepository(Category::class)->find($id);
       
       $editForm = $this->createForm(CategoryType::class, $category);
       
       $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $em = $this->getDoctrine()->getManager()->flush();
           return $this->redirectToRoute('category_show', array('id' => $id));
       }
       
       return $this->render('category/edit.html.twig', [
           'id' => $id,
           'edit_form' => $editForm->createView()
       ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $em->remove($category);
        $em->flush();
        
        $this->addFlash(
            'error',
            'Category deleted'
        );
        
        return $this->redirectToRoute('category_index');
    }

    /**
     * @Route("/category/{id}", name="category_show")
     */
    public
    function showAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    
}