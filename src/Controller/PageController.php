<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    /**
     * @return Response
     */
    public function blog()
    {
        return $this->render('Page\blog.html.twig');
    }

    /**
     * @return Response
     */
    public function event()
    {
        return $this->render('Page\event.html.twig');
    }

    /**
     * @return Response
     */
    public function project()
    {
        return $this->render('Page\project.html.twig');
    }

    /**
     * @param $name
     * @return Response
     */
    public function lire($name)
    {
        try {
            return $this->render('Page\Articles/'.strtolower($name).'.html.twig');
        } catch (\Exception $e) {
            $response = new Response(null, 404);
            return $this->render('Page\not_found.html.twig', array(), $response);
        }

    }
}
