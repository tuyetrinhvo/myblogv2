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
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class UserController
 *
 * @category PHP_Class
 * @package  AppBundle\Controller
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
class UserController extends Controller
{
    /**
     * Function listAction
     *
     * @Route("/blog/users", name="user_list")
     * @Method({"GET"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return          Response
     */
    public function listAction()
    {
        return $this->render(
            'user/list.html.twig', [
                'users' => $this->getDoctrine()
                    ->getRepository('AppBundle:User')
                    ->findAll()
            ]
        );
    }

    /**
     * Function createAction
     *
     * @param Request $request Some argument description
     *
     * @Route("/blog/users/create", name="user_create")
     * @Method({"GET",         "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('article_list');
        }

        return $this->render(
            'user/create.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Function editAction
     *
     * @param User    $user    Some argument description
     * @param Request $request Some argument description
     *
     * @Route("/blog/users/{slug}/edit", name="user_edit")
     * @Method({"GET",            "POST"})
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(User $user, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') ||
            ($user->getUsername() === $this->get('security.token_storage')->getToken()->getUsername())) {


        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('article_list'); }

         } else{

            $this->addFlash(
                'error',
                'Vous ne pouvez pas modifier cet utilisateur car vous n\'êtes pas son créateur.');

            return $this->redirectToRoute('article_list');
        }

        return $this->render(
            'user/edit.html.twig', [
                'form' => $form->createView(), 'user' => $user
            ]
        );

    }

    /**
     * Function deleteUserAction
     *
     * @param User $user Some argument description
     *
     * @Route("/blog/users/{slug}/delete", name="user_delete")
     * @Method({"GET",              "POST"})
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteArticleAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');

        return $this->redirectToRoute('user_list');

    }
}
