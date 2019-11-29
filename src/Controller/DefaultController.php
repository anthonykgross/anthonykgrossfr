<?php

namespace App\Controller;

use App\Algolia\API;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class DefaultController extends AbstractController
{
    private static $questions = array(
        array("trois plus un est égale à "      => array("4", "quatre")),
        array("huit moins trois est égale à "   => array("5", "cinq")),
        array("trois moins un est égale à " => array("2", "deux")),
        array("zéro plus un est égale à " => array("1", "un")),
        array("trois plus zéro est égale à " => array("3", "trois"))
    );

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
     * @throws \AlgoliaSearch\AlgoliaException
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

    /**
     * @param Request $request
     * @param MailerInterface $mailer
     * @return JsonResponse
     */
    public function sendMail(Request $request, MailerInterface $mailer)
    {
        $e          = array('msg' => array());
        $subject    = $request->get('subject', null);
        $email      = trim($request->get('email', null));
        $name       = trim($request->get('name', null));
        $msg        = $request->get('message', null);
        $captcha_id = $request->get('captcha_id', null);
        $captcha    = $request->get('captcha', null);
        
        if (is_null($subject) || strlen($subject)==0) {
            $e['msg'][] = "Un petit effort, précisez moi le sujet de votre message";
        }
        if (is_null($msg) || strlen($msg)==0) {
            $e['msg'][] = "Y a-t-il une raison de m'envoyer un message vide ?";
        }
        if (is_null($email) || strlen($email)==0) {
            $e['msg'][] = "Sans email, je ne pourrais jamais vous répondre .. ";
        }
        if (is_null($name) || strlen($name)==0) {
            $e['msg'][] = "Pouvez vous me renseigner au moins votre prénom s'il vous plait ? ";
        }
        if (is_null($captcha) || strlen($captcha)==0) {
            $e['msg'][] = "Je vous prierais de confirmer que vous n'êtes pas un bot (captcha).";
        }
        if (is_null($captcha_id) || strlen($captcha_id)==0) {
            $e['msg'][] = "Impossible de savoir si vous utilisez le bon captcha.";
        }
        if (!is_null($captcha) && !is_null($captcha_id)) {
            $response =  array_values(self::$questions[$captcha_id]);
            
            if (!in_array($captcha, $response[0])) {
                $e['msg'][] = "Votre réponse au captcha ne correspond pas à ce que je m'attendais.";
            }
        }
        
        if (count($e['msg'])==0) {
            try {

                $message = (new Email())
                    ->subject("[AnthonyKGross.fr] - ".$subject)
                    ->from('no-reply@anthonykgross.fr')
		            ->replyTo(new Address($email, $name))
                    ->to('anthony.k.gross@gmail.com')
                    ->html($this->renderView('Email\owner.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $mailer->send($message);

                $message = (new Email())
                    ->subject("[AnthonyKGross.fr] - Prise de contact")
                    ->from('no-reply@anthonykgross.fr')
		            ->replyTo(new Address("anthony.k.gross@gmail.com", "Anthony K GROSS"))
                    ->to($email)
                    ->html($this->renderView('Email\client.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $mailer->send($message);
                $e          = array('msg' => array("J'ai bien reçu votre message. Merci beaucoup !"));
                $view       = new JsonResponse($e, 200);
            } catch (\Exception $e) {
                $e          = array(
                    'msg' => array(
                        "Votre email n'est pas envoyé. Tenteriez-vous de me renseigner de fausses informations ? =) "
                    )
                );
                $view       = new JsonResponse($e, 500);
            }
            return $view;
        }
        $view       = new JsonResponse($e, 500);
        return $view;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function captcha(Request $request)
    {
        $response = new JsonResponse();
        if ($request->isXmlHttpRequest()) {
            $idx      = rand(0, count(self::$questions)-1);
            $response->setData(array(
                'idx'       => $idx,
                'question'  => array_keys(self::$questions[$idx])[0]
            ));
        }
        return $response;
    }
}
