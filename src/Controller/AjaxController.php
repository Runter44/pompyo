<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Slugger;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/existe-email/", name="verificationDoublonEmail")
     * @param Request $request
     * @return Response
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

    /**
     * @Route("/ajax/upload-image/", name="uploadImage")
     * @param Request $request
     * @return Response
     */
    public function uploadImage(Request $request)
    {
        if ($request->files->get("imageUpload") != null) {
            $image = $request->files->get("imageUpload");
            $nomImage = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($this->getParameter('images_articles_directory'), $nomImage);
            return new Response($nomImage);
        }
        return new Response("Mauvaise requête !", 400);
    }
}
