<?php

namespace Anthonykgrossfr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function blogAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Page:blog.html.twig');
    }
    
    public function eventAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Page:event.html.twig');
    }
    
    public function projectAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Page:project.html.twig');
    }
    
    public function lireAction($name)
    {
        if (!$this->get('templating')->exists('AnthonykgrossfrMainBundle:Page:Articles/'.strtolower($name).'.html.twig') ) {
            return $this->render('AnthonykgrossfrMainBundle:Page:not_found.html.twig');
        }
        return $this->render('AnthonykgrossfrMainBundle:Page:Articles/'.strtolower($name).'.html.twig');
    }
}
