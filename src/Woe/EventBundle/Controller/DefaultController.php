<?php

namespace Woe\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Woe\EventBundle\Entity\Notification;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('WoeEventBundle:Event')->findAllActiveSortedByDate();
        $tags = $em->getRepository('WoeEventBundle:Tag')->findRandomWithCount();

        return $this->renderPaginatedEvents($request, $events, $tags);
    }

    public function eventAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('WoeEventBundle:Event')->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Renginys nerastas');
        }

        $notification = new Notification();
        $form = $this->createForm('notification', $notification, array('event' => $event));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $notification->setEvent($event);
            $em->persist($notification);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                'Priminimas sėkmingai išsaugotas. Jį gausite nurodytu adresu ' . $notification->getDate()->format("Y-m-d H:i")
            );

            return $this->redirect($this->generateUrl('woe_web_event', array('id' => $id)));
        }

        return $this->render('WoeWebBundle:Body:event.html.twig', array(
            'event' => $event,
            'form'  => $form->createView(),
        ));
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $this->get('woe_event.event_search')->getSearchResults($request->query->get('q'));
        $tags = $em->getRepository('WoeEventBundle:Tag')->findRandomWithCount();
        return $this->renderPaginatedEvents($request, $events, $tags);
    }

    public function tagAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('WoeEventBundle:Event')->findByTagId($id);
        $tags = $em->getRepository('WoeEventBundle:Tag')->findRandomWithCount();

        return $this->renderPaginatedEvents($request, $events, $tags);
    }

    /**
     * Render event list with pagination
     *
     * @param Request $request
     * @param $events
     * @param $tags
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderPaginatedEvents(Request $request, $events, $tags)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events,
            $request->query->get('page', 1),
            15
        );

        return $this->render('WoeWebBundle:Body:index.html.twig', array('pagination' => $pagination, 'tags' => $tags));
    }
}
