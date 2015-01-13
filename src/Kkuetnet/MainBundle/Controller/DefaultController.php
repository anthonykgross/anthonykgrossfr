<?php

namespace Kkuetnet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KkuetnetMainBundle:Default:index.html.twig');
    }
}
