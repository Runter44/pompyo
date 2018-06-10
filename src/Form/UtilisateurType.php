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
                'placeholder' => 'Entrez une adresse e-mail valide',
                'maxlength' => '100',
                'autocomplete' => 'email',
              ),
            ))
            ->add('prenom', TextType::class, array(
              'label' => 'Prénom',
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez le prénom de la personne',
                'maxlength' => '50',
                'autocomplete' => 'given-name',
              ),
            ))
            ->add('nom', TextType::class, array(
              'label' => 'Nom',
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez le nom de la personne',
                'maxlength' => '50',
                'autocomplete' => 'family-name',
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
