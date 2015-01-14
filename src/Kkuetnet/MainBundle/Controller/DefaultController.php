<?php

namespace Kkuetnet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KkuetnetMainBundle:Default:index.html.twig');
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
                    ->setSubject("[Kkuet.net] - ".$subject)
                    ->setFrom('no-reply@kkuet.net')
                    ->setTo('anthony.k.gross@gmail.com')
                    ->setBody($this->renderView('KkuetnetMainBundle:Default:email.html.twig', array(
                        'subject'   => $subject,
                        'email'     => $email,
                        'name'      => $name,
                        'message'   => $msg
                    )), 'text/html')
                ;
                $this->get('mailer')->send($message);

                $message = \Swift_Message::newInstance()
                    ->setSubject("[Kkuet.net] - Prise de contact")
                    ->setFrom('no-reply@kkuet.net')
                    ->setTo($email)
                    ->setBody($this->renderView('KkuetnetMainBundle:Default:email-client.html.twig', array(
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
