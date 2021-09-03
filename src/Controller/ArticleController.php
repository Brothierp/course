<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("", name="article")
     */
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/ajouter", name="ajouter")
     */
    public function ajouter(Request $request, EntityManagerInterface  $em): Response
    {
        $article = new article();
        $addArticle = $this->createForm(ArticleType::class, $article);
        $addArticle->handleRequest($request); // hydrater $article
        if ($addArticle->isSubmitted()) {

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article');
        }
        return $this->render(
            'article/ajouter.html.twig',
            ['addArticle' => $addArticle->createView()]
        );
    }

    /**
     * @Route("article/modifier/{id}", name="modifier")
     */
    public function modifier(Article $article, Request $request, EntityManagerInterface  $em): Response
    {

        $addArticle = $this->createForm(ArticleType::class, $article);
        $addArticle->handleRequest($request); // hydrater $article
        if ($addArticle->isSubmitted()) {

            $em->flush();
            return $this->redirectToRoute('article');
        }
        return $this->render(
            'article/modifier.html.twig',
            ['addArticle' => $addArticle->createView()]
        );
    }

    /**
     * @Route("article/delete/{id}", name="delete")
     */
    public function delete(Article $article, EntityManagerInterface  $em): Response
    {

        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('article');
    }
}



