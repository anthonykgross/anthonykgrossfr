<?php

namespace Anthonykgrossfr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    static $questions = array(
        array("trois plus un est égale à "      => array("4", "quatre")),
        array("huit moins trois est égale à "   => array("5", "cinq")),
        array("trois moins un est égale à " => array("2", "deux")),
        array("zéro plus un est égale à " => array("1", "un")),
        array("trois plus zéro est égale à " => array("3", "trois"))
    );
    
    public function indexAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Default:index.html.twig');
    }

    public function projectAction(){
        return $this->redirect($this->generateUrl('anthonykgrossfr_project'), 301); 
    }
    
    public function eventAction(){
        return $this->redirect($this->generateUrl('anthonykgrossfr_event'), 301); 
    }
    
    public function sendmailAction()
    {
        $e          = array('msg' => array());
        $request    = $this->container->get('request');
        
        $subject    = $request->get('subject',  null);
        $email      = trim($request->get('email', null));
        $name       = trim($request->get('name', null));
        $msg        = $request->get('message', null);
        $captcha_id = $request->get('captcha_id', null);
        $captcha    = $request->get('captcha', null);
        
        if(is_null($subject) || strlen($subject)==0){
            $e['msg'][] = "Un petit effort, précisez moi le sujet de votre message";
        }
        if(is_null($msg) || strlen($msg)==0){
            $e['msg'][] = "Y a-t-il une raison de m'envoyer un message vide ?";
        }
        if(is_null($email) || strlen($email)==0){
            $e['msg'][] = "Sans email, je ne pourrais jamais vous répondre .. ";
        }
        if(is_null($name) || strlen($name)==0){
            $e['msg'][] = "Pouvez vous me renseigner au moins votre prénom s'il vous plait ? ";
        }
        if(is_null($captcha) || strlen($captcha)==0){
            $e['msg'][] = "Je vous prierais de confirmer que vous n'êtes pas un bot (captcha).";
        }
        if(is_null($captcha_id) || strlen($captcha_id)==0){
            $e['msg'][] = "Impossible de savoir si vous utilisez le bon captcha.";
        }
        if(!is_null($captcha) && !is_null($captcha_id)){
            $response =  array_values(self::$questions[$captcha_id]);
            
            if(!in_array($captcha, $response[0])){
                $e['msg'][] = "Votre réponse au captcha ne correspond pas à ce que je m'attendais.";
            }
        }
        
        if(count($e['msg'])==0){
            try{
                $message = \Swift_Message::newInstance()
                    ->setSubject("[AnthonyKGross.fr] - ".$subject)
                    ->setFrom('no-reply@anthonykgross.fr')
		    ->setReplyTo(array($email => $name))
                    ->setTo('anthony.k.gross@gmail.com')
                    ->setBody($this->renderView('AnthonykgrossfrMainBundle:Default:email.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $this->get('mailer')->send($message);

                $message = \Swift_Message::newInstance()
                    ->setSubject("[AnthonyKGross.fr] - Prise de contact")
                    ->setFrom('no-reply@anthonykgross.fr')
		    ->setReplyTo(array("anthony.k.gross@gmail.com" => "Anthony K GROSS"))
                    ->setTo($email)
                    ->setBody($this->renderView('AnthonykgrossfrMainBundle:Default:email-client.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $this->get('mailer')->send($message);
                $e          = array('msg' => array("J'ai bien reçu votre message. Merci beaucoup !"));
                $view       = new \Symfony\Component\HttpFoundation\JsonResponse($e, 200);
            }
            catch(\Exception $e){
                $e          = array('msg' => array("Votre email n'est pas envoyé. Tenteriez-vous de me renseigner de fausses informations ? =) "));
                $view       = new \Symfony\Component\HttpFoundation\JsonResponse($e, 500);
            }
            return $view;
        }
        $view       = new \Symfony\Component\HttpFoundation\JsonResponse($e, 500);
        return $view;
    }
    
    public function cvAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Default:cv.html.twig');
    }
    
    public function captchaAction(){
        $request    = $this->container->get('request');
        if ($request->isXmlHttpRequest()) {
            $idx      = rand(0, count(self::$questions)-1);
            $response = new JsonResponse();
            return $response->setData(array(
                'idx'       => $idx,
                'question'  => array_keys(self::$questions[$idx])[0]
            ));
        }
    }
}
