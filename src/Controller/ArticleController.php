<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use App\Utils\Slugger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

class ArticleController extends Controller
{
    /**
     * @Route("/articles/", name="articles")
     */
    public function articles()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAllOrderedByDate();

        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/nouveau/", name="nouvelArticle")
     * @param Request $request
     * @param Slugger $slugger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function nouvelArticle(Request $request, Slugger $slugger)
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // upload miniature image
            $image = $form->get("miniature")->getData();
            $nomImage = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('miniatures_directory'), $nomImage);
            $article->setMiniature($nomImage);

            // on insère les paramètres prédéfinis
            $article->setAuteur($user);
            $article->setDateCreation(new \DateTime());
            $article->setDateModif(new \DateTime());
            $article->setUrl($slugger->genererSlug($article->getTitre()));

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('voirArticle', [
                'url' => $article->getUrl()
            ]);
        }

        return $this->render('article/nouvelArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/articles/modifier/{url}/", name="modifierArticle")
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifierArticle(Article $article, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // upload miniature image
            $image = $form->get("miniature")->getData();
            $nomImage = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move($this->getParameter('miniatures_directory'), $nomImage);
            $article->setMiniature($nomImage);

            $article->setDateModif(new \DateTime());

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('voirArticle', [
                'url' => $article->getUrl()
            ]);
        }

        return $this->render('article/modifierArticle.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/articles/{url}/", name="voirArticle")
     * @param $article
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function voirArticle(Article $article)
    {
        return $this->render('article/voirArticle.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/articles/supprimer/{id}/", name="supprimerArticle", methods="DELETE")
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function supprimerArticle(Article $article, Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('articles');
    }
}
