<?php

namespace App\Controller;

use App\Entity\Page;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
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
     * @param null $url
     * @return Response
     */
    public function index($url = null)
    {
        if (!is_null($url) && strlen($url) == 0) {
            $url = null;
        }

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(Page::class)->findOneBy([
            'url' => $url,
            'isOnline' => true
        ]);

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
     * @return RedirectResponse
     */
    public function project()
    {
        return $this->redirect($this->generateUrl('anthonykgrossfr_project'), 301);
    }

    /**
     * @return RedirectResponse
     */
    public function event()
    {
        return $this->redirect($this->generateUrl('anthonykgrossfr_event'), 301);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendmail(Request $request)
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

                $message = (new Swift_Message("[AnthonyKGross.fr] - ".$subject))
                    ->setFrom('no-reply@anthonykgross.fr')
		            ->setReplyTo(array(
		                $email => $name
                    ))
                    ->setTo('anthony.k.gross@gmail.com')
                    ->setBody($this->renderView('Default\email.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $this->get('mailer')->send($message);

                $message = (new Swift_Message("[AnthonyKGross.fr] - Prise de contact"))
                    ->setFrom('no-reply@anthonykgross.fr')
		            ->setReplyTo(array(
		                "anthony.k.gross@gmail.com" => "Anthony K GROSS"
                    ))
                    ->setTo($email)
                    ->setBody($this->renderView('Default\email-client.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $this->get('mailer')->send($message);
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
     * @return Response
     */
    public function cv()
    {
        return $this->render('Default\cv.html.twig');
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
