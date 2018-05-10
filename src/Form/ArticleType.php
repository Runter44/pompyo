<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
              'label' => 'Titre de l\'article',
              'label_attr' => array(
                'class' => 'col-sm-2 col-form-label',
              ),
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez le nom de l\'article',
                'maxlength' => '125',
              ),
            ))
            ->add('description', TextareaType::class, array(
              'label' => 'Description de l\'article',
              'label_attr' => array(
                'class' => 'col-sm-2 col-form-label',
              ),
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez un court résumé de l\'article',
                'maxlength' => '500',
                'rows' => '5',
              ),
            ))
            ->add('miniature', FileType::class, array(
              'label' => 'Choisissez une image',
              'label_attr' => array(
                'class' => 'custom-file-label',
                'id' => 'nomMiniatureInput',
              ),
              'required' => false,
              'attr' => array(
                'class' => 'custom-file-input',
                'accept' => 'image/jpeg',
              ),
            ))
            ->add('contenu', TextareaType::class, array(
              'label' => 'Contenu de l\'article',
              'label_attr' => array(
                'class' => 'col-sm-2 col-form-label align-middle d-inline'
              ),
              'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Entrez le contenu de l\'article',
                'rows' => '50',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
