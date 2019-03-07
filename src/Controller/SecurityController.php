<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @throws \Exception
     * @Route("/admin/add", name="add_admin")
     */
    public function addAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $admin = new Users();
        $admin->setName('admin');
        $admin->setFirstname('admin');
        $admin->setEmail('admin@greenworld.com');
        $admin->setPassword($passwordEncoder->encodePassword($admin,'root'));
        $admin->setRoles('ROLE_ADMIN');
        $admin->setDateCreation(new \DateTime('now'));
        $admin->setActive(true);
        $admin->setIsDoubleAuth(false);

        $entityManager->persist($admin);
        $entityManager->flush();

        return new Response('admin ajouté');
    }

    /**
     * @param Request $request
     * @Route("/user/addPassword/{key}/{email}", name="password_user")
     */
    public function addPasswordUser(Request $request,$key,$email)
    {
        $form = $this->createFormBuilder()
            ->add('email',EmailType::class)
            ->add('password',PasswordType::class)
            ->add('key',TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form-> isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $email2 = $data['email'];
            $password = password_hash($data['password'],PASSWORD_BCRYPT);
            $keyInput = $data['key'];

            if(sha1($keyInput) === $key && sha1($email2) === $email) {
                $emailUser = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['email'=>$email2]);
                $mail = $emailUser->getEmail();


                $em = $this->getDoctrine()->getManager();
                $query = $em->getRepository(Users::class)->createQueryBuilder('')
                    ->update(Users::class,'u')
                    ->set('u.password',':password')
                    ->setParameter('password',$password)
                    ->where('u.email = :email')
                    ->setParameter('email',$mail)
                    ->getQuery();

                $query->execute();
            } else {
                return new Response('erreur');
            }






        }
        return $this->render('security/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
