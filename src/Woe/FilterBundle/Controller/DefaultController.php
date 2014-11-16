<?php

namespace Woe\FilterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WoeFilterBundle:Default:index.html.twig', array('name' => $name));
    }
}
