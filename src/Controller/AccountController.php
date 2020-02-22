<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher le formulaire de login et de se connecter
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();


        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se deconnecter
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return void
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez vous connecter !"
            );

            return $this->redirectToRoute("account_login");
        }

        return $this->render("account/registration.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de modification du profile
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function profile(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                "Les données ont été modifiées avec succès !"
            );
        }

        return $this->render("account/profile.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mdp
     * 
     * @Route("/account/password-update", name="account_update_password")
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, UserInterface $user, EntityManagerInterface $em)
    {
        $passwordUpdate = new PasswordUpdate;

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1 verifier le mot de passe actuel
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Ce mot de passe n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié :)"
                );

                return $this->redirectToRoute("homepage");
            }
        }

        return $this->render("account/updatepassword.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher le profil de l'utilisateur
     * 
     * @Route("/account", name="account_myaccount")
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function myAccount()
    {
        return $this->render("user/show.html.twig", [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'afficher la liste des réservations de l'utilisateur
     * 
     * @Route("/account/bookings", name="account_bookings")
     *
     * @return void
     */
    public function bookings()
    {
        return $this->render('account/bookings.html.twig');
    }
}
