<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

class AdministrationController extends Controller
{
    /**
     * @Route("/administration/", name="administration")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
}
