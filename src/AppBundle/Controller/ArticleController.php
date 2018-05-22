<?php
/**
 * Class Doc Controller
 *
 * PHP version 7.0
 *
 * @category PHP_Class
 * @package  AppBundle
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  tuyetrinhvo@2018
 * @link     Link Name
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Article;
use AppBundle\Form\Type\ArticleType;
use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArticleController
 *
 * @category PHP_Class
 * @package  AppBundle\Controller
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  tuyetrinhvo@2018
 * @link     Link Name
 */
class ArticleController extends Controller
{
    /**
     * Function listArticleAction
     *
     * @Route("/blog/posts", name="article_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listArticleAction(Request $request)
    {
        $query = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->findAll();

        $paginator = $this->get('knp_paginator');

        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',9)
            );

        return $this->render(
            'article/list.html.twig', ['articles' => $articles]);
    }

    /**
     * Function showArticleAction
     *
     * @param Article    $article    Some argument description
     * @param Request $request Some argument description
     *
     * @Route("/blog/posts/{title}", name="article_show")
     *  @Method({"GET",         "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function showAction(Request $request, $title)
    {
        $article= $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Article')
            ->findOneBy(['title' => $title]);

        if (null === $article) {
            throw new NotFoundHttpException("L'article avec le titre ".$title." n'existe pas.");
        }
        $query = $article->getComments();

        $paginator = $this->get('knp_paginator');

        $Comments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $postComment = $form->getData();
            $postComment->setAuthor($this->getUser());
            $postComment->setArticle($article);

            $em = $this->getDoctrine()->getManager();
            $em->persist($postComment);
            $em->flush();

            $this->addFlash('success', 'Le Commentaire a été bien posté !');
            return $this->redirectToRoute('article_show', [
                'title' => $title]
            );
        }

        return $this->render(
            'article/show.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
                'Comments' => $Comments,
            ]
        );
    }

    /**
     * Function createArticleAction
     *
     * @param Request $request Some argument description
     *
     * @Route("/blog/posts/create/", name="article_create")
     * @Method({"GET",         "POST"})
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createArticleAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article->setAuthor($this->getUser());

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'L\'article a été bien été ajouté.');

            return $this->redirectToRoute('article_list');
        }

        return $this->render(
            'article/create.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Function editArticleAction
     *
     * @param Article    $article    Some argument description
     * @param Request $request Some argument description
     *
     * @Route("/blog/posts/{title}/edit", name="article_edit")
     * @Method({"GET",            "POST"})
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editArticleAction(Article $article, Request $request)
    {
        if ($article->getAuthor()->getUsername() !== $this->get('security.token_storage')->getToken()->getUser()->getUsername()) {

            $this->addFlash(
                'error',
                'Vous ne pouvez pas modifier cet article car vous n\'êtes pas son auteur.'
            );

            return $this->redirectToRoute('article_list');
        }

            $form = $this->createForm(ArticleType::class, $article);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'L\'article a bien été modifié.');

                return $this->redirectToRoute('article_list');
            }

            return $this->render(
                'article/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'article' => $article,
                ]
            );

    }


    /**
     * Function deleteArticleAction
     *
     * @param Article $article Some argument description
     *
     * @Route("/blog/posts/{title}/delete", name="article_delete")
     * @Method({"GET",              "POST"})
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction(Article $article)
    {
        if ($article->getAuthor()->getUsername() !== $this->get('security.token_storage')->getToken()->getUser()->getUsername()) {

            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer cet article car vous n\'êtes pas son auteur.'
            );

            return $this->redirectToRoute('article_list');
        }

            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'L\'article a bien été supprimé.');

            return $this->redirectToRoute('article_list');

    }
}