<?php

namespace App\Admin;

use App\Entity\Utilisateur;
use App\Utils\PasswordGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UtilisateurAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('delete');
        $collection->remove('export');
    }

    protected function configureFormFields(FormMapper $form)
    {
        if ($this->isCurrentRoute('create')) {
            $form
                ->add('prenom', TextType::class, array(
                    'label' => 'Prénom'
                ))
                ->add('nom', TextType::class, array(
                    'label' => 'Nom'
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'Adresse e-mail'
                ));
        }

        $form
            ->add('role', ChoiceType::class, array(
                'label' => 'Rôle',
                'choices' => array(
                    "Utilisateur simple (ROLE_USER)" => "ROLE_USER",
                    "Utilisateur privilégié (ROLE_PRIVE)" => "ROLE_PRIVE",
                    "Administrateur (ROLE_ADMIN)" => "ROLE_ADMIN",
                    "Super administrateur (ROLE_SUPER_ADMIN)" => "ROLE_SUPER_ADMIN",
                )
            ));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('prenom', null, array(
                'label' => 'Prénom'
            ))
            ->add('nom')
            ->add('email', null, array(
                'label' => 'Adresse e-mail'
            ));
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('prenom', null, array(
                'label' => 'Prénom'
            ))
            ->addIdentifier('nom')
            ->addIdentifier('email', null, array(
                'label' => 'Adresse e-mail'
            ))
            ->addIdentifier('dateInscription', null, array(
                'label' => 'Date d\'inscription',
                'pattern' => "dd MMMM yyyy à HH:mm:ss"
            ))
            ->addIdentifier('role', null, array(
                'label' => 'Rôle'
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                )
            ));
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('prenom', null, array(
                'label' => 'Prénom'
            ))
            ->add('nom', null, array(
                'label' => 'Nom'
            ))
            ->add('email', null, array(
                'label' => 'Adresse e-mail'
            ))
            ->add('dateInscription', null, array(
                'label' => 'Date d\'inscription'
            ))
            ->add('role', null, array(
                'label' => 'Rôle'
            ))
            ->add('inscriptionEvenements', null, array(
                'label' => 'Inscriptions aux événements'
            ));
    }

    public function prePersist($object)
    {
        $container = $this->getConfigurationPool()->getContainer();

        $object->setDateInscription(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $password = (new PasswordGenerator())->randomPassword();
        $passwordHash = $container->get('security.password_encoder')->encodePassword($object, $password);
        $object->setPassword($passwordHash);

        $body = $container->get('twig')->render('emails/inscription.html.twig',
            array(
                'user' => $object,
                'password' => $password
            ));

        $message = (new \Swift_Message("Bienvenue sur le site internet des Sapeurs-Pompiers de Saint Julien de Concelles !"))
            ->setFrom(['pompiersstjuliendeconcelles@gmail.com' => 'Sapeurs-Pompiers de Saint Julien de Concelles'])
            ->setTo([$object->getEmail() => $object->getPrenom() . ' ' . $object->getNom()])
            ->setBody($body, 'text/html');

        $container->get('mailer')->send($message);
    }

    public function toString($object)
    {
        return $object instanceof Utilisateur ? $object->getPrenom() . " " . $object->getNom() : "Utilisateur";
    }
}