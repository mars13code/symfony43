<?php

namespace App\Form;

use App\Entity\Contenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('uri')
            ->add('code')
            ->add('imageSrc')
            ->add('categorie')
            // sera automatiquement initialisé 
            // à la date actuelle dans le constructeur
            //->add('dateCreation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contenu::class,
        ]);
    }
}
