<?php

namespace Woe\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WoeWebBundle:Default:index.html.twig');
    }
}