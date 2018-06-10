<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use \Datetime;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     *
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @param AuthorizationCheckerInterface $authChecker
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker)
    {
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') == true) {
            throw new NotFoundHttpException();
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('connexion/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
