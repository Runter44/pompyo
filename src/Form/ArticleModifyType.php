<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\DataTransformer\FileToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, array(
                'label' => 'Titre de l\'article',
                'label_attr' => array(
                    'class' => 'col-sm-2 col-form-label text-bleu',
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
                    'class' => 'col-sm-2 col-form-label text-bleu',
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un court résumé de l\'article',
                    'maxlength' => '500',
                    'rows' => '5',
                ),
            ))
            ->add('contenu', TextareaType::class, array(
                'label' => 'Contenu de l\'article',
                'label_attr' => array(
                    'class' => 'col-sm-2 col-form-label align-middle d-inline text-bleu'
                ),
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le contenu de l\'article',
                    'rows' => '50',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
