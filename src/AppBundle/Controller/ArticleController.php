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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/articles", name="article_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listArticleAction()
    {
        return $this->render(
            'article/list.html.twig', [
                'articles' => $this->getDoctrine()
                    ->getRepository('AppBundle:Article')
                    ->findAll()
            ]
        );
    }

    /**
     * Function createArticleAction
     *
     * @param Request $request Some argument description
     *
     * @Route("/articles/create", name="article_create")
     * @Method({"GET",         "POST"})
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
     * @Route("/articles/{id}/edit", name="article_edit")
     * @Method({"GET",            "POST"})
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
     * @Route("/articles/{id}/delete", name="article_delete")
     * @Method({"GET",              "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction(Article $article)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer cet article car vous n\'êtes pas administrateur.'
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