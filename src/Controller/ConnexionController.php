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

    /**
     * @Route("/inscription/", name="inscription")
     *
     * @param Request $request
     * @param AuthorizationCheckerInterface $authChecker
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pageInscription(Request $request, AuthorizationCheckerInterface $authChecker, UserPasswordEncoderInterface $encoder)
    {
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') == true) {
            throw new NotFoundHttpException();
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cleSecrete = "6LfIXFgUAAAAADfzW3W3MMJFQ2zftK65t8LH4sDs";
            $reponseCaptcha = $_POST['g-recaptcha-response'];

            $stream_opts = [
                "ssl" => [
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ],
            ];

            $urlVerifyCaptcha = "https://www.google.com/recaptcha/api/siteverify?secret=".$cleSecrete."&response=".$reponseCaptcha;
            $decode = json_decode(file_get_contents($urlVerifyCaptcha, false, stream_context_create($stream_opts)), true);

            if ($decode["success"] == true) {
                $entityManager = $this->getDoctrine()->getManager();

                $password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
                $utilisateur->setPassword($password);
                $utilisateur->setRole("ROLE_USER");
                $utilisateur->setDateInscription(new \DateTime('now', new \DateTimeZone("Europe/Paris")));

                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $token = new UsernamePasswordToken($utilisateur, null, 'main', $utilisateur->getRoles());
                $this->container->get('security.token_storage')->setToken($token);
                $this->container->get('session')->set('_security_main', serialize($token));

                return $this->redirectToRoute('accueil');
            } else {
                $this->addFlash('error', "Erreur lors de la validation du Captcha. Avez-vous coché la case ?");
                return $this->redirectToRoute('inscription');
            }
        }

        return $this->render('connexion/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
