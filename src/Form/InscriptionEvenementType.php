<?php

namespace App\Form;

use App\Entity\InscriptionEvenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionEvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbAdultes', NumberType::class, array(
                'label' => 'Nombre d\'adultes présents',
                'invalid_message' => 'Vous devez entrer un nombre valide',
                'attr' => array(
                    'placeholder' => 'Nombre d\'adultes'
                )
            ))
            ->add('nbEnfants', NumberType::class, array(
                'label' => 'Nombre d\'enfants présents (-12 ans)',
                'invalid_message' => 'Vous devez entrer un nombre valide',
                'attr' => array(
                    'placeholder' => 'Nombre d\'enfants'
                )
            ))
            ->add('commentaires', TextareaType::class, array(
                'label' => 'Commentaires',
                'attr' => array(
                    'placeholder' => 'Veuillez renseigner des éléments importants à nous transmettre',
                    'rows' => '3',
                    'maxlength' => '500',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InscriptionEvenement::class,
        ]);
    }
}
