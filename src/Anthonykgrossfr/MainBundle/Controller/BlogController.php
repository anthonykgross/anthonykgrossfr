<?php

namespace Anthonykgrossfr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Blog:index.html.twig');
    }
    
    public function lireAction($name)
    {
        if (!$this->get('templating')->exists('AnthonykgrossfrMainBundle:Blog:Articles/'.strtolower($name).'.html.twig') ) {
            return $this->render('AnthonykgrossfrMainBundle:Blog:not_found.html.twig');
        }
        return $this->render('AnthonykgrossfrMainBundle:Blog:Articles/'.strtolower($name).'.html.twig');
    }
}
