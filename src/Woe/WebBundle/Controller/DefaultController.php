<?php

namespace Woe\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('WoeWebBundle:Body:index.html.twig');
    }

    public function eventAction($id)
    {
	return $this->render('WoeWebBundle:Body:event.html.twig');
    }

    public function adminAction()
    {
   	return $this->render('WoeWebBundle:Body:admin.html.twig');
    }
}
