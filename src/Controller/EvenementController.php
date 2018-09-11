<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\InscriptionEvenement;
use App\Form\EvenementType;
use App\Form\InscriptionEvenementType;
use App\Repository\EvenementRepository;
use App\Utils\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenements")
 */
class EvenementController extends Controller
{
    /**
     * @Route("/", name="evenement_index", methods="GET")
     *
     * @param EvenementRepository $evenementRepository
     * @return Response
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        foreach ($evenementRepository->findAll() as $evenement) {
            if ($evenement->getDateDebut() < new \DateTime()) {
                foreach ($evenement->getInscriptionEvenements() as $inscriptionEvenement) {
                    $this->getDoctrine()->getManager()->remove($inscriptionEvenement);
                }
                $this->getDoctrine()->getManager()->remove($evenement);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        return $this->render('evenement/index.html.twig', ['evenements' => $evenementRepository->findAllOrderedByDate()]);
    }

    /**
     * @Route("/{slug}/", name="evenement_show", methods="GET|POST")
     *
     * @param Evenement $evenement
     * @param Request $request
     * @return Response
     */
    public function show(Evenement $evenement, Request $request): Response
    {
        if ($evenement->getRoleMinimum() === 'ROLE_PRIVE') {
            $this->denyAccessUnlessGranted('ROLE_PRIVE');
        }

        $inscriptionEvenement = $this->getDoctrine()->getRepository(InscriptionEvenement::class)->findOneBy([
            "evenement" => $evenement,
            "utilisateur" => $this->getUser(),
        ]);

        if ($inscriptionEvenement == null) {
            $inscriptionEvenement = new InscriptionEvenement();
        }

        $inscriptionEvenementForm = $this->createForm(InscriptionEvenementType::class, $inscriptionEvenement);

        $inscriptionEvenementForm->handleRequest($request);

        if ($inscriptionEvenementForm->isSubmitted() && $inscriptionEvenementForm->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_PRIVE');

            $inscriptionEvenement->setUtilisateur($this->getUser());
            $inscriptionEvenement->setEvenement($evenement);
            $inscriptionEvenement->setDateInscription(new \DateTime('now', new \DateTimeZone("Europe/Paris")));

            $em = $this->getDoctrine()->getManager();
            $em->persist($inscriptionEvenement);
            $em->flush();

            $this->addFlash('success', 'Votre inscription a bien été prise en compte.');

            return $this->redirectToRoute('evenement_show', ["slug" => $evenement->getSlug()]);
        }

        return $this->render('evenement/show.html.twig', ['evenement' => $evenement, 'form' => $inscriptionEvenementForm->createView(), 'inscription' => $inscriptionEvenement]);
    }

    /**
     * @Route("/{slug}/participants/", name="evenement_participants", methods="GET|POST")
     *
     * @param Evenement $evenement
     * @return Response
     */
    public function participants(Evenement $evenement)
    {
        if ($evenement->getInscriptionPossible() === false) {
            throw new NotFoundHttpException();
        }
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('evenement/participants.html.twig', ['evenement' => $evenement]);
    }

    /**
     * @Route("/{slug}/supprimer_inscription", name="inscription_evenement_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Evenement $evenement
     * @return Response
     */
    public function deleteInscription(Request $request, Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted("ROLE_PRIVE");
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            $inscription = $em->getRepository(InscriptionEvenement::class)->findOneBy([
                "utilisateur" => $this->getUser(),
                "evenement" => $evenement
            ]);

            $em->remove($inscription);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_show', ["slug" => $evenement->getSlug()]);
    }
}
