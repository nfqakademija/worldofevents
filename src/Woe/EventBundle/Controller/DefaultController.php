<?php

namespace Woe\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $events = $this->getDoctrine()->getManager()
            ->getRepository('WoeEventBundle:Event')
            ->findAll();

        return $this->renderPaginatedEvents($request, $events);
    }

    public function eventAction($id)
    {
        $event = $this->getDoctrine()->getManager()
            ->getRepository('WoeEventBundle:Event')
            ->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Renginys nerastas');
        }

        return $this->render('WoeWebBundle:Body:event.html.twig', array('event' => $event));
    }

    public function searchAction(Request $request)
    {
        $events = $this->get('woe_event.event_search')->getSearchResults($request->query->get('q'));
        return $this->renderPaginatedEvents($request, $events);
    }

    /**
     * Render event list with pagination
     *
     * @param Request $request
     * @param $events
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderPaginatedEvents(Request $request, $events)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $request->query->get('page', 1),
            15
        );

        return $this->render('WoeWebBundle:Body:index.html.twig', array('pagination' => $pagination));
    }
}
