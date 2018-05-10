<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use App\Entity\Utilisateur;
use App\Utils\Slugger;
use App\Utils\TimeAgo;
use App\Utils\BBCoder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @param AuthorizationCheckerInterface $authChecker
     * @param UserInterface $user
     * @param Slugger $slugger
     * @param BBCoder $bbcoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function nouvelArticle(Request $request, AuthorizationCheckerInterface $authChecker, UserInterface $user, Slugger $slugger, BBCoder $bbcoder)
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {

          $article = new Article();
          $form = $this->createForm(ArticleType::class, $article);

          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();

              // upload miniature image
              $image = $form->get("miniature")->getData();
              $nomImage = md5(uniqid()).'.'.$image->guessExtension();
              $image->move($this->getParameter('miniatures_directory'), $nomImage);
              $article->setMiniature($nomImage);

              // on insère les paramètres prédéfinis
              $article->setAuteur($user);
              $article->setContenuBbcode($article->getContenu());
              $article->setContenu($bbcoder->bbcodeToHtml($article->getContenuBbcode()));
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
        return $this->redirectToRoute('articles');
    }

    /**
     * @Route("/articles/modifier/{slug}/", name="modifierArticle")
     */
    public function modifierArticle($slug, Request $request, AuthorizationCheckerInterface $authChecker, UserInterface $user, Slugger $slugger, BBCoder $bbcoder)
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {

          $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(["url" => $slug]);
          $article->setContenu($article->getContenuBbcode());
          $article->setMiniature(new File($this->getParameter('miniatures_directory').'/'.$article->getMiniature()));
          $form = $this->createForm(ArticleType::class, $article);

          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();

              // upload miniature image
              $image = $form->get("miniature")->getData();
              $nomImage = md5(uniqid()).'.'.$image->guessExtension();
              $image->move($this->getParameter('miniatures_directory'), $nomImage);
              $article->setMiniature($nomImage);

              // on insère les paramètres prédéfinis
              $article->setContenuBbcode($article->getContenu());
              $article->setContenu($bbcoder->bbcodeToHtml($article->getContenuBbcode()));
              $article->setDateModif(new \DateTime());

              $entityManager->persist($article);
              $entityManager->flush();

              return $this->redirectToRoute('voirArticle', [
                  'url' => $article->getUrl()
              ]);
          }

          if ($form->isSubmitted() && !$form->isValid()) {
              $this->addFlash('error', $form->getErrors());
          }

          return $this->render('article/modifierArticle.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
          ]);
        }
        return $this->redirectToRoute('articles');
    }

    /**
     * @Route("/articles/{url}/", name="voirArticle")
     */
    public function voirArticle($url, TimeAgo $time)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy([
          "url" => $url,
        ]);
        return $this->render('article/voirArticle.html.twig', [
          'article' => $article,
        ]);
    }

    /**
     * @Route("/articles/supprimer/{id}/", name="supprimerArticle")
     */
    public function supprimerArticle($id, AuthorizationCheckerInterface $authChecker)
    {
        if ($authChecker->isGranted('ROLE_ADMIN')) {
          $entityManager = $this->getDoctrine()->getManager();
          $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

          $entityManager->remove($article);
          $entityManager->flush();
        }
        return $this->redirectToRoute('articles');
    }
}
