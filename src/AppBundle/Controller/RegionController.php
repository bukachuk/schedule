<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Region;
use AppBundle\Form\Type\RegionType;
use AppBundle\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class RegionController extends Controller
{
    /**
     * @Route("/admin/region", requirements={"page": "\d+"}, name="admin_region")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('AppBundle:Region')->getPaginate();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query,
                $request->query->get('page', 1),
                10
        );
        return $this->render('Region/index.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/admin/region/add", name="admin_add_region")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($region);
            $em->flush();
            return $this->redirectToRoute('admin_region');
        }

        return $this->render('Region/add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/region/edit/{id}", requirements={"id": "\d+"}, name="admin_edit_region")
     * @param Request $request
     * @param Region $region
     * @return Response|RedirectResponse
     */
    public function editAction(Region $region, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($region);
            $em->flush();
            return $this->redirectToRoute('admin_region');
        }

        return $this->render('Region/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/region/search", name="admin_region_search")
     */
    public function searchAction(Request $request, RegionRepository $regionRepository)
    {
        $response = [];
        if($term = $request->get('term')){
            $results = $regionRepository->search($term);
            foreach($results as $item){
                $response[] = ['label' => $item->getName(), 'delivery' => $item->getDelivery()];
            }
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/region/delete/{id}", requirements={"id": "\d+"}, name="admin_delete_region")
     * @param Region $region
     * @return Response|RedirectResponse
     */
    public function deleteAction(Region $region, EntityManagerInterface $em)
    {
        try {
            $em->remove($region);
            $em->flush();
        } catch (\Exception $e){
            $this->addFlash('warning', 'Невозможно удалить запись, тк есть записи использующие ее');
        }

        return $this->redirectToRoute('admin_region');
    }
}
