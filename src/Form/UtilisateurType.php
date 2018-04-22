<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
              'label' => 'Adresse e-mail',
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez votre adresse e-mail',
                'maxlength' => '100',
                'autocomplete' => 'email',
              ),
            ))
            ->add('password', RepeatedType::class, array(
              'type' => PasswordType::class,
              'first_options' => array(
                'label' => 'Mot de passe',
                'attr' => array(
                  'class' => 'form-control',
                  'placeholder' => 'Votre mot de passe',
                  'maxlength' => '20',
                  'pattern' => '.{8,20}',
                  'autocomplete' => 'new-password',
                ),
              ),
              'second_options' => array(
                'label' => 'Confirmation',
                'attr' => array(
                  'class' => 'form-control',
                  'placeholder' => 'Confirmation de votre mot de passe',
                  'maxlength' => '20',
                  'pattern' => '.{8,20}',
                  'autocomplete' => 'new-password',
                ),
              ),
            ))
            ->add('prenom', TextType::class, array(
              'label' => 'Prénom',
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez votre prénom',
                'maxlength' => '50',
                'autocomplete' => 'given-name',
              ),
            ))
            ->add('nom', TextType::class, array(
              'label' => 'Nom',
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez votre nom',
                'maxlength' => '50',
                'autocomplete' => 'family-name',
              ),
            ))
            ->add('dateDeNaissance', BirthdayType::class, array(
              'widget' => 'single_text',
              'input' => 'datetime',
              'label' => 'Date de naissance',
              'attr' => array(
                'class' => 'form-control',
                'min' => '1900-01-01',
                'max' => (new \DateTime())->format("Y-m-d"),
                'autocomplete' => 'bday',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
