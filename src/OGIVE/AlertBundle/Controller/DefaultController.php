<?php

namespace OGIVE\AlertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OGIVEAlertBundle:Default:index.html.twig');
    }
}
