<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    /**
     * @return Response
     */
    public function blogAction()
    {
        return $this->render('AppBundle:Page:blog.html.twig');
    }

    /**
     * @return Response
     */
    public function eventAction()
    {
        return $this->render('AppBundle:Page:event.html.twig');
    }

    /**
     * @return Response
     */
    public function projectAction()
    {
        return $this->render('AppBundle:Page:project.html.twig');
    }

    /**
     * @param $name
     * @return Response
     */
    public function lireAction($name)
    {
        if (!$this->get('templating')->exists('AppBundle:Page:Articles/'.strtolower($name).'.html.twig') ) {
            $response = new Response(null, 404);
            return $this->render('AppBundle:Page:not_found.html.twig', array(), $response);
        }
        return $this->render('AppBundle:Page:Articles/'.strtolower($name).'.html.twig');
    }
}
