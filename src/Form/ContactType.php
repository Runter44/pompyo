<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, array(
                'label' => 'PRÉNOM',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre prénom',
                    'maxlength' => '255',
                ),
            ))
            ->add('nom', TextType::class, array(
                'label' => 'NOM',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre nom',
                    'maxlength' => '255',
                ),
            ))
            ->add('email', EmailType::class, array(
                'label' => 'ADRESSE E-MAIL',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre adresse e-mail',
                    'maxlength' => '255',
                ),
            ))
            ->add('phone', TelType::class, array(
                'label' => 'TÉLÉPHONE (optionnel)',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'maxlength' => '255',
                    'pattern' => '(0|\\+33)[1-9][0-9]{8}',
                ),
                'required' => false,
            ))
            ->add('object', TextType::class, array(
                'label' => 'OBJET',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'objet de votre message',
                    'maxlength' => '255',
                ),
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'VOTRE MESSAGE',
                'label_attr' => array(
                    'class' => 'text-danger',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez votre message',
                    'rows' => '20',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
