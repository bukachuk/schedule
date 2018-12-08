<?php

namespace AppBundle\Controller;

use AppBundle\Filter\RoutesFilter;
use AppBundle\Form\Type\RoutesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\Type\FilterType;
use AppBundle\Entity\Routes;

class RoutesController extends Controller
{
    /**
     * @Route("/", requirements={"page": "\d+"}, name="admin_route")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $routesFilter = new RoutesFilter();
        $form = $this->createForm(FilterType::class, $routesFilter);
        $form->handleRequest($request);

        $query = $this->getDoctrine()->getRepository('AppBundle:Routes')->getPaginate($routesFilter);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query,
                $request->query->get('page', 1),
                20
        );
        return $this->render('Routes/index.html.twig', array('pagination' => $pagination, 'form' => $form->createView()));
    }

    /**
     * @Route("/admin/route/add", name="admin_add_routes")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $route = new Routes();
        $form = $this->createForm(RoutesType::class, $route);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($route);
            $em->flush();
            return $this->redirectToRoute('admin_route');
        }

        return $this->render('Routes/add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/route/edit/{routes}", requirements={"routes": "\d+"}, name="admin_edit_routes")
     * @param Request $request
     * @param Route $route
     * @return Response|RedirectResponse
     */
    public function editAction(Routes $routes, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RoutesType::class, $routes);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($routes);
            $em->flush();
            return $this->redirectToRoute('admin_route');
        }

        return $this->render('Routes/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin/route/delete/{routes}", requirements={"routes": "\d+"}, name="admin_delete_routes")
     * @param Route $route
     * @return Response|RedirectResponse
     */
    public function deleteAction(Routes $routes, EntityManagerInterface $em)
    {
        $em->remove($routes);
        $em->flush();
        return $this->redirectToRoute('admin_route');
    }
}
