<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdFormType extends AbstractType
{
    /**
     * Permet de retourner la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', TextType::class, 
                $this->getConfiguration("Titre", "Tapez un titre accrocheur pour votre annonce !"))
            ->add(
                'slug', TextType::class, 
                $this->getConfiguration("Chaîne URL", "Adresse Web (Automatique)", [
                    'required' => false
                ]))
            ->add(
                'coverImage', UrlType::class, 
                $this->getConfiguration("Url de l'image à la une", "Une image attrayante"))
            ->add(
                'introduction', TextType::class, 
                $this->getConfiguration("Introduction", "Donnez une description globale de votre annonce"))
            ->add(
                'content', TextareaType::class, 
                $this->getConfiguration("Description détaillée", "Donnez vraiment envie de venir chez vous") )
            ->add(
                'rooms', IntegerType::class, 
                $this->getConfiguration("Nombre de chambres", "Donnez ici le nombre de chambres à louer"))
            ->add(
                'price', MoneyType::class, 
                $this->getConfiguration("Prix par nuit", "Indiquez votre prix"))
            ->add(
                'images', CollectionType::class,
                [
                    'entry_type' => ImageFormType::class,
                    'allow_add' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
