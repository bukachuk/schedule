<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Courier;
use AppBundle\Form\Type\CourierType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CourierController extends Controller
{
    /**
     * @Route("/admin/courier", requirements={"page": "\d+"}, name="admin_courier")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('AppBundle:Courier')->getPaginate();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query,
                $request->query->get('page', 1),
                10
        );
        return $this->render('Courier/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/admin/courier/add", name="admin_add_courier")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $courier = new Courier();
        $form = $this->createForm(CourierType::class, $courier);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($courier);
            $em->flush();
            return $this->redirectToRoute('admin_courier');
        }

        return $this->render('Courier/add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/courier/edit/{id}", requirements={"id": "\d+"}, name="admin_edit_courier")
     * @param Request $request
     * @param Courier $courier
     * @return Response|RedirectResponse
     */
    public function editAction(Courier $courier, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CourierType::class, $courier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($courier);
            $em->flush();
            return $this->redirectToRoute('admin_courier');
        }

        return $this->render('Courier/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/courier/delete/{id}", requirements={"id": "\d+"}, name="admin_delete_courier")
     * @param Courier $courier
     * @return Response|RedirectResponse
     */
    public function deleteAction(Courier $courier, EntityManagerInterface $em)
    {
        try {
            $em->remove($courier);
            $em->flush();
        } catch (\Exception $e){
            $this->addFlash('warning', 'Невозможно удалить запись, тк есть записи использующие ее');
        }

        return $this->redirectToRoute('admin_courier');
    }
}
