<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Supplier;
use App\Form\SupplierType;

class SupplierController extends AbstractController
{
    /**
    * @Route("/supplier",name="supplier_index")
    */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $suppliers = $em->getRepository(Supplier::class)->findAll();

        return $this->render('supplier/index.html.twig', array(
            'suppliers' => $suppliers,
        ));
    }

    /**
    * @Route("/supplier/create", name="supplier_create", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplier);
            $em->flush();
            return $this->redirectToRoute('supplier_show', array('id' => $supplier->getId()));
        }
        return $this->render('supplier/create.html.twig', array(
            'supplier' => $supplier,
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/supplier/edit/{id}", name="supplier_edit", methods={"GET","POST"})
    */
    public function editAction(Request $request, $id)
   {
       $em = $this->getDoctrine()->getManager();
       $supplier = $em->getRepository(Supplier::class)->find($id);
       
       $editForm = $this->createForm(SupplierType::class, $supplier);
       
       $editForm->handleRequest($request);
        if($editForm->isSubmitted() && $editForm->isValid()){
            $em = $this->getDoctrine()->getManager()->flush();
           return $this->redirectToRoute('supplier_show', array('id' => $id));
       }
       
       return $this->render('supplier/edit.html.twig', [
           'id' => $id,
           'edit_form' => $editForm->createView()
       ]);
    }

    /**
     * @Route("/supplier/delete/{id}", name="supplier_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository(Supplier::class)->find($id);
        $em->remove($supplier);
        $em->flush();
        
        $this->addFlash(
            'error',
            'Supplier deleted'
        );
        
        return $this->redirectToRoute('supplier_index');
    }

    /**
     * @Route("/supplier/{id}", name="supplier_show")
     */
    public
    function showAction($id)
    {
        $supplier = $this->getDoctrine()
            ->getRepository(Supplier::class)
            ->find($id);

        return $this->render('supplier/show.html.twig', [
            'supplier' => $supplier
        ]);
    }
}
