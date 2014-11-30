<?php

namespace Woe\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function adminAction()
    {
        return $this->render('WoeWebBundle:Body:admin.html.twig');
    }
}
