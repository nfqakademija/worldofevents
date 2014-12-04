<?php

namespace Woe\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $events = $this->getDoctrine()->getManager()
            ->getRepository('WoeEventBundle:Event')
            ->findAll();
        return $this->render('WoeWebBundle:Body:index.html.twig', array('events' => $events));
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
        return $this->render(
            'WoeWebBundle:Body:index.html.twig',
            array('events' => $events)
        );
    }
}
