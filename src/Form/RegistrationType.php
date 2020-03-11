<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Url Profile", "URL de votre Profile"))
            ->add('hash', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne sont pas identiques, merci de recommencer !",
                'first_options' => $this->getConfiguration("Mot de passe", "Choisissez un bon mot de passe"),
                'second_options' => $this->getConfiguration("Confirmez le mot de passe", "Resaisissez votre mot de passe")
            ])
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Résumez-vous ..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Votre profile détaillé"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
