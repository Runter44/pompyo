<?php

namespace App\Admin;

use App\Utils\Slugger;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class EvenementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Propriétés', ['class' => 'col-md-8'])
                ->add('nom')
                ->add('lieu')
                ->add('visiblePublic', null, array(
                    'label' => 'Événement public'
                ))
                ->add('inscriptionPossible', null, array(
                    'label' => 'Inscriptions autorisées',
                    'help' => 'Seuls les comptes ayant accès aux éléments privés du site pourront s\'inscrire'
                ))
            ->end()
            ->with('Date', ['class' => 'col-md-4'])
                ->add('dateDebut', DateType::class, array(
                    'label' => 'Date de début',
                    'format' => 'dd MMMM yyyy',
                    'years' => range(date('Y'), date('Y')+10)
                ))
                ->add('heureDebut', TimeType::class, array(
                    'label' => 'Heure de début',
                ))
                ->add('dateLimiteInscription', DateType::class, array(
                    'label' => 'Date limite de l\'inscription',
                    'required' => false,
                    'format' => 'dd MMMM yyyy',
                    'years' => range(date('Y'), date('Y')+10),
                    'help' => 'Ce champ ne sera pris en compte que si les inscriptions sont autorisées'
                ))
            ->end()
            ->with('Contenu')
                ->add('teaser', null, array(
                    'label' => 'Résumé'
                ))
                ->add('description', CKEditorType::class, array(
                    'base_path' => 'ckeditor',
                    'js_path'   => 'ckeditor/ckeditor.js',
                    'config' => array(
                        'removeButtons' => "Subscript,Superscript,Anchor,Styles,Cut,Copy,Paste,PasteText,PasteFromWord,Source,Maximize,Image"
                    )
                ))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('nom', null, array(
                'label' => 'Nom'
            ))
            ->add('lieu', null, array(
                'label' => 'Lieu'
            ))
            ->add('dateDebut', null, array(
                'label' => 'Date de début'
            ))
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('nom', null, array(
                'label' => 'Nom de l\'événement'
            ))
            ->add('lieu', null, array(
                'label' => 'Lieu'
            ))
            ->add('dateDebut', null, array(
                'label' => 'Date de début'
            ))
            ->add('heureDebut', null, array(
                'label' => 'Heure de début'
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                )
            ))
        ;
    }

    public function getExportFields()
    {
        return [ 'nom', 'lieu', 'dateDebut', 'heureDebut', 'visiblePublic', 'inscriptionPossible', 'dateLimiteInscription' ];
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('nom')
            ->add('dateDebut', null, array(
                'label' => 'Date de début'
            ))
            ->add('heureDebut', null, array(
                'label' => 'Heure de début'
            ))
            ->add('lieu')
            ->add('visiblePublic', null, array(
                'label' => 'Événement public'
            ))
            ->add('inscriptionPossible', null, array(
                'label' => 'Inscriptions autorisées'
            ))
            ->add('dateLimiteInscription', null, array(
                'label' => 'Date limite de l\'inscription'
            ))
            ->add('teaser', null, array(
                'label' => 'Résumé'
            ))
            ->add('description', 'html')
        ;
    }

    public function prePersist($object)
    {
        $slugger = new Slugger();

        if ($object->getVisiblePublic() === false) {
            $object->setRoleMinimum('ROLE_PRIVE');
        } else {
            $object->setRoleMinimum('ROLE_USER');
        }
        $object->setSlug($slugger->genererSlug($object->getNom()));

        if ($object->getInscriptionPossible() === false) {
            $object->setDateLimiteInscription(null);
        } elseif ($object->getInscriptionPossible() === true && $object->getDateLimiteInscription() === null) {
            $object->setDateLimiteInscription($object->getDateDebut());
        }

        if ($object->getDateLimiteInscription() != null && ($object->getDateLimiteInscription() > $object->getDateDebut())) {
            $object->setDateLimiteInscription($object->getDateDebut());
        }
    }

    public function preUpdate($object)
    {
        $slugger = new Slugger();

        if ($object->getVisiblePublic() === false) {
            $object->setRoleMinimum('ROLE_PRIVE');
        } else {
            $object->setRoleMinimum('ROLE_USER');
        }
        $object->setSlug($slugger->genererSlug($object->getNom()));

        if ($object->getInscriptionPossible() === false) {
            $object->setDateLimiteInscription(null);
        } elseif ($object->getInscriptionPossible() === true && $object->getDateLimiteInscription() === null) {
            $object->setDateLimiteInscription($object->getDateDebut());
        }

        if ($object->getDateLimiteInscription() != null && ($object->getDateLimiteInscription() > $object->getDateDebut())) {
            $object->setDateLimiteInscription($object->getDateDebut());
        }
    }

    public function toString($object)
    {
        return $object->getNom();
    }
}