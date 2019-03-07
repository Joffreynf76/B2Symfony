<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Users;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

  class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("admin/test/message", name="message")
     */
    public function message()
    {
        $user = $this->getUser();

        $id = $user->getId();
        $countMessage = $this->getDoctrine()->getRepository(Message::class)->count(['status'=>null,'receiver'=>$id]);
        return $this->render('message.html.twig',[
            'message' => 'Un message',
            'titre' => 'Un titre en gros',
            'nonLu'=>$countMessage
        ]);
    }

    /**
     * @Route("admin/inbox", name="admin_inbox")
     */
    public function inbox()
    {
        $user = $this->getUser();

        $id = $user->getId();
        $message = $this->getDoctrine()->getRepository(Message::class)->findNotRead($id);

        return $this->render('inbox.html.twig',['message'=>$message]);
    }

    /**
     * @Route("admin/message/read/{id}",name="read_message")
     */
    public function read($id)
    {
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);
        $message->setStatus(true);

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return new Response('Message marqué comme lu');
    }

    /**
     * @return Response
     * @Route("/admin/2fa/check",name="check_2fa")
     */
    public function check2fa(Request $request, GoogleAuthenticatorInterface $authenticator)
    {
        $form = $this->createFormBuilder()
            ->add('code', TextType::class)
            ->add('check', SubmitType::class, ['label' => 'Vérifier code'])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $key = $data['code'];

            $user = $this->getUser();
            $id = $user->getId();



            $currentUser = $this->getDoctrine()->getRepository(Users::class)->find($id);
            $code = $currentUser->getGoogleAuthenticatorSecret();


            if($authenticator->checkCode($user,$key)){
                echo 'Code ok !!!';
            } else {
                echo 'Erreur code';
            }

        }

        return $this->render('security/2fa.html.twig', [
            'form' => $form->createView(),
        ]);
    }



  }
