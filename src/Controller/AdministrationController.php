<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Utils\PasswordGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministrationController extends Controller
{
    /**
     * @Route("/administration/", name="administration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param PasswordGenerator $passwordGenerator
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, PasswordGenerator $passwordGenerator, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new Utilisateur();

        $formInscription = $this->createForm(UtilisateurType::class, $user);

        $formInscription->handleRequest($request);

        if ($formInscription->isSubmitted() && $formInscription->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setDateInscription(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $user->setRole('ROLE_USER');
            $password = $passwordGenerator->randomPassword();
            $passwordHash = $encoder->encodePassword($user, $password);
            $user->setPassword($passwordHash);

            $em->persist($user);
            $em->flush();

            $message = (new \Swift_Message("Bienvenue sur le site internet des Sapeurs-Pompiers de Saint Julien de Concelles !"))
                ->setFrom(['pompiersstjuliendeconcelles@gmail.com' => 'Sapeurs-Pompiers de Saint Julien de Concelles'])
                ->setTo([$user->getEmail() => $user->getPrenom() . ' ' . $user->getNom()])
                ->setBody(
                    $this->renderView(
                        'emails/inscription.html.twig',
                        array('user' => $user, 'adder' => $this->getUser(), 'password' => $password)
                    ),'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', "L'utilisateur a bien été ajouté");

            return $this->redirectToRoute('administration');
        }

        return $this->render('administration/index.html.twig', [
            'formInscription' => $formInscription->createView(),
        ]);
    }
}
