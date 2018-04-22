<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/existe-email/", name="verificationDoublonEmail")
     */
    public function verificationDoublonEmail(Request $request)
    {
        if ($request->request->get("email")) {
          $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(
            ['email' => $request->request->get("email")]
          );
          if ($user !== null) {
            return new Response($user->getEmail());
          } else {
            return new Response("");
          }
        }
        return new Response("Mauvaise requête !", 400);
    }
}
