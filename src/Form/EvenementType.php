<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
                'attr' => array(
                    'placeholder' => 'Nom de l\'événement',
                    'maxlength' => '100',
                ),
            ))
            ->add('lieu', TextType::class, array(
                'label' => 'Lieu',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
                'attr' => array(
                    'placeholder' => 'Lieu de l\'événement',
                    'maxlength' => '255',
                ),
            ))
            ->add('teaser', TextareaType::class, array(
                'label' => 'Résumé',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
                'attr' => array(
                    'placeholder' => 'Résumé de l\'événement : en quoi il consiste, ce qu\'on peut y faire...',
                    'maxlength' => '400',
                    'rows' => '3',
                ),
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
                'attr' => array(
                    'placeholder' => 'Description complète de l\'événement : des informations particulières à savoir, une version longue du résumé...',
                    'rows' => '10',
                )
            ))
            ->add('inscriptionPossible', CheckboxType::class, array(
                'label' => 'Les inscriptions pour cet événement sont autorisées',
                'required' => false,
            ))
            ->add('dateLimiteInscription', DateType::class, array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date limite de l\'inscription',
                'label_attr' => array(
                    'id' => 'evenement_dateLimiteInscription_label',
                    'class' => 'text-bleu mb-0',
                ),
                'required' => false,
                'attr' => array(
                    'min' => (new \DateTime())->format("Y-m-d"),
                )
            ))
            ->add('visiblePublic', CheckboxType::class, array(
                'label' => 'Cet événement est public',
            ))
            ->add('dateDebut', DateType::class, array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Jour de début de l\'événement',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
                'attr' => array(
                    'min' => (new \DateTime())->format("Y-m-d"),
                )
            ))
            ->add('heureDebut', TimeType::class, array(
                'label' => 'Heure de début de l\'événement',
                'widget' => 'single_text',
                'input' => 'datetime',
                'label_attr' => array(
                    'class' => 'text-bleu'
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
