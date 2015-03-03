<?php

namespace Anthonykgrossfr\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AnthonykgrossfrMainBundle:Default:index.html.twig');
    }

    public function sendmailAction()
    {
        $e          = array('msg' => array());
        $request    = $this->container->get('request');
        
        $subject    = $request->get('subject', 'Aucun sujet');
        $email      = trim($request->get('email', 'anthony.k.gross@gmail.com'));
        $name       = trim($request->get('name', 'Inconnu'));
        $msg        = $request->get('message', 'Aucun sujet');
        
        if(strlen($email)==0){
            $e['msg'][] = " Sans email, je ne pourrais jamais vous répondre .. ";
        }
        if(strlen($name)==0){
            $e['msg'][] = "Pouvez vous me renseigner au moins votre prénom s'il vous plait ? ";
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
            }
            catch(\Exception $e){
                $e          = array('msg' => array("Votre email n'est pas envoyé. Tenteriez-vous de me renseigner de fausses informations ? =) "));
            }
        }
        $view       = new \Symfony\Component\HttpFoundation\JsonResponse($e);
        return $view;
    }
}
