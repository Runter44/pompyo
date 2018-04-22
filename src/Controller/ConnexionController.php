<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use \Datetime;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ConnexionController extends Controller
{
  /**
  * @Route("/connexion/", name="connexion")
  */
  public function connexion(Request $request, AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker)
  {
    if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') == true) {
      return $this->redirectToRoute('accueil');
    }
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();
    return $this->render('connexion/connexion.html.twig', [
      'last_username' => $lastUsername,
      'error'         => $error,
    ]);
  }

  /**
  * @Route("/inscription/", name="inscription")
  */
  public function pageInscription(Request $request, AuthorizationCheckerInterface $authChecker, UserPasswordEncoderInterface $encoder)
  {
    if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') == true) {
      return $this->redirectToRoute('accueil');
    }

    $utilisateur = new Utilisateur();
    $form = $this->createForm(UtilisateurType::class, $utilisateur);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $cleSecrete = "6Lc7r1QUAAAAAPW3T7wnpJmdddeH_h8tiLbzgm0M";
      $reponseCaptcha = $_POST['g-recaptcha-response'];
      $remoteip = $_SERVER['REMOTE_ADDR'];
      $urlVerifyCaptcha = "https://www.google.com/recaptcha/api/siteverify?secret=".$cleSecrete."&response=".$reponseCaptcha."&remoteip=".$remoteip;
      $decode = json_decode(file_get_contents($urlVerifyCaptcha), true);

      if ($decode['success'] == true) {
        $entityManager = $this->getDoctrine()->getManager();

        $password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
        $utilisateur->setPassword($password);
        $utilisateur->setRole("ROLE_USER");

        $entityManager->persist($utilisateur);
        $entityManager->flush();

        $token = new UsernamePasswordToken($utilisateur, null, 'main', $utilisateur->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        $this->container->get('session')->set('_security_main', serialize($token));

        return $this->redirectToRoute('accueil');
      } else {
        return $this->redirectToRoute('inscription');
      }
    }

    return $this->render('connexion/inscription.html.twig', [
      'form' => $form->createView(),
    ]);
  }
  }
