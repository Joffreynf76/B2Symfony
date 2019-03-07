<?php
/**
 * Created by PhpStorm.
 * User: joffrey
 * Date: 2019-03-04
 * Time: 15:15
 */

namespace App\Controller;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\AdminLoginForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
 final class AdminLoginController extends AbstractController
{
     /**
      * @var AuthenticationUtils
      */
     private $authenticationUtils;

     public function __construct(AuthenticationUtils $authenticationUtils)
     {
         $this->authenticationUtils = $authenticationUtils;
     }

     /**
      * @Route("/admin/login", name="admin_login")
      */
     public function loginAction(): Response
     {
         $form = $this->createForm(AdminLoginForm::class, [
             'email' => $this->authenticationUtils->getLastUsername()
         ]);

         return $this->render('security/login.html.twig', [
             'last_username' => $this->authenticationUtils->getLastUsername(),
             'form' => $form->createView(),
             'error' => $this->authenticationUtils->getLastAuthenticationError(),
         ]);
     }

     /**
      * @Route("/admin/logout", name="admin_logout")
      */
     public function logoutAction(): void
     {
         // Left empty intentionally because this will be handled by Symfony.
     }

     /**
      * @Route("admin/googleSecret", name="generate_google_secret")
      */
     public function googleSecret(GoogleAuthenticatorInterface $googleAuthenticator)
     {
         return new Response($googleAuthenticator->generateSecret());
     }

     /**
      * @Route("/2fa/accessible", name="2fa_accessible_route")
      */
     public function accessibleDuring2fa()
     {
         return new Response("It works!");
     }

     /**
      * @param GoogleAuthenticatorInterface $authenticator
      * @Route("admin/qrcode", name="qrcode")
      */
     public function qrcode(GoogleAuthenticatorInterface $authenticator)
     {
         $user = $this->getUser();
         $url = $authenticator->getUrl($user);
         echo '<img src="'.$url.'" />';

         return new Response();
     }
}