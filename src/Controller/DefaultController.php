<?php

namespace App\Controller;

use AlgoliaSearch\AlgoliaException;
use App\Algolia\API;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DefaultController extends AbstractController
{
    public function removeTrailingSlash(Request $request)
    {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);

        return $this->redirect($url, 301);
    }

    /**
     * @param Request $request
     * @param API $api
     * @return Response
     * @throws AlgoliaException
     */
    public function search(Request $request, API $api)
    {
        $search = $request->query->get('q');
        $result = $api->search($search);
        $noResult = false;

        if ($result['nbHits'] == 0) {
            $noResult = true;
            $result = $api->search('');
        }

        return $this->render(
            'search.html.twig',
            [
                'result' => $result['hits'],
                'noResult' => $noResult,
            ]
        );

    }

    /**
     * @param null $url
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @return Response
     */
    public function index($url = null, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!is_null($url) && strlen($url) == 0) {
            $url = null;
        }

        $filters = [
            'url' => $url,
            'isOnline' => true
        ];

        // Show all pages. (Draft mode)
        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            unset($filters['isOnline']);
        }

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(Page::class)->findOneBy($filters);

        if (!$page) {
            $response = new Response();
            $response->setStatusCode(404);
            return $this->render('not_found.html.twig', [], $response);
        }

        return $this->render(
            $page->getTemplate()->getFile(),
            ['entity' => $page]
        );
    }
}
