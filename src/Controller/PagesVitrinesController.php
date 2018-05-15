<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesVitrinesController extends Controller
{
    /**
     * @Route("/le-centre/", name="leCentre")
     */
    public function leCentre()
    {
        return $this->render('pages_vitrines/centre.html.twig', [
            'controller_name' => 'PagesVitrinesController',
        ]);
    }

    /**
     * @Route("/nous-rejoindre/", name="recrutement")
     */
    public function recrutement()
    {
        return $this->render('pages_vitrines/recrutement.html.twig', [
            'controller_name' => 'PagesVitrinesController',
        ]);
    }
}
