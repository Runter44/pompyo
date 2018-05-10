<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Utils\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('evenement/index.html.twig', ['evenements' => $evenementRepository->findAllOrderedByDate()]);
    }

    /**
     * @Route("/nouveau/", name="evenement_new", methods="GET|POST")
     *
     * @param Request $request
     * @param Slugger $slugger
     * @return Response
     */
    public function new(Request $request, Slugger $slugger): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $evenement = new Evenement();
        $evenement->setInscriptionPossible(true);
        $evenement->setVisiblePublic(true);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($evenement->getVisiblePublic() === false) {
                $evenement->setRoleMinimum('ROLE_PRIVE');
            } else {
                $evenement->setRoleMinimum('ROLE_USER');
            }
            $evenement->setSlug($slugger->genererSlug($evenement->getNom()));

            if ($evenement->getInscriptionPossible() === false) {
                $evenement->setDateLimiteInscription(null);
            }

            if ($evenement->getDateLimiteInscription() != null && ($evenement->getDateLimiteInscription() > $evenement->getDateDebut())) {
                $evenement->setDateLimiteInscription($evenement->getDateDebut());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/", name="evenement_show", methods="GET")
     *
     * @param Evenement $evenement
     * @return Response
     */
    public function show(Evenement $evenement): Response
    {
        if ($evenement->getRoleMinimum() === 'ROLE_PRIVE') {
            $this->denyAccessUnlessGranted('ROLE_PRIVE');
        }
        return $this->render('evenement/show.html.twig', ['evenement' => $evenement]);
    }

    /**
     * @Route("/{slug}/modifier/", name="evenement_edit", methods="GET|POST")
     *
     * @param Request $request
     * @param Evenement $evenement
     * @param Slugger $slugger
     * @return Response
     */
    public function edit(Request $request, Evenement $evenement, Slugger $slugger): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($evenement->getVisiblePublic() === false) {
                $evenement->setRoleMinimum('ROLE_PRIVE');
            } else {
                $evenement->setRoleMinimum('ROLE_USER');
            }
            $evenement->setSlug($slugger->genererSlug($evenement->getNom()));

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_show', ['slug' => $evenement->getSlug()]);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/", name="evenement_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Evenement $evenement
     * @return Response
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }
}
