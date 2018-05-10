<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends Controller
{
    /**
     * @Route("/contact/", name="contact")
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param AuthorizationCheckerInterface $authChecker
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(ValidatorInterface $validator, Request $request, AuthorizationCheckerInterface $authChecker, UserInterface $user = null, \Swift_Mailer $mailer)
    {
        $contact = new Contact();

        if ($authChecker->isGranted('ROLE_USER')) {
            $contact->setPrenom($user->getPrenom());
            $contact->setNom($user->getNom());
            $contact->setEmail($user->getUsername());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (count($validator->validate($contact)) > 0) {
                $this->addFlash('error', 'Erreur : veuillez vérifier les informations fournies');
            } else {
                $cleSecrete = '6LevW1gUAAAAANQdp3FOMrWOhEdcJzzms59JuYTC';
                $reponseCaptcha = $_POST['g-recaptcha-response'];

                $stream_opts = [
                    "ssl" => [
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ],
                ];

                $urlVerifyCaptcha = "https://www.google.com/recaptcha/api/siteverify?secret=".$cleSecrete."&response=".$reponseCaptcha;
                $decode = json_decode(file_get_contents($urlVerifyCaptcha, false, stream_context_create($stream_opts)), true);

                if ($decode['success'] === true) {
                $contact->setDateEnvoi(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                    $contact->setIpEnvoi($request->getClientIp());
                    $entityManager = $this->getDoctrine()->getManager();

                    $message = (new \Swift_Message('Demande de contact'))
                        ->setFrom(['pompiersstjuliendeconcelles@gmail.com' => 'Sapeurs-Pompiers de Saint Julien de Concelles'])
                        ->setTo(['pompiersstjuliendeconcelles@gmail.com' => 'Sapeurs-Pompiers de Saint Julien de Concelles'])
                        ->setReplyTo([$contact->getEmail() => $contact->getPrenom() . " " . $contact->getNom()])
                        ->setBody(
                            $this->renderView(
                                'emails/contact.html.twig',
                                array('contact' => $contact)
                            ), 'text/html'
                        );

                    $envoye = $mailer->send($message);

                    if ($envoye == 0) {
                        $this->addFlash('error', 'Votre message n\'a pas été envoyé. Veuillez réessayer.');
                        $contact->setEnvoye(false);
                    } else {
                        $this->addFlash('success', 'Votre message a bien été envoyé.');
                        $contact->setEnvoye(true);
                    }

                    $entityManager->persist($contact);
                    $entityManager->flush();
                    return $this->redirectToRoute('accueil');
                } else {
                    $this->addFlash('error', 'Veuillez cocher la case "Je ne suis pas un robot"');
                }
            }
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur : veuillez vérifier les informations fournies');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
