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

use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\ContactType;
use Swift_Message;

/**
 * Class ContactController
 *
 * @category PHP_Class
 * @package  AppBundle\Controller
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  tuyetrinhvo@2018
 * @link     Link Name
 */
class ContactController extends Controller
{
    /**
     * Function contactAction
     *
     * @param Request $request Some argument description
     *
     * @Route("/contact", name="contact")
     * @Method({"GET",         "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $request->request->get('form');

            $mail = \Swift_Message::newInstance()
                ->setSubject('Message depuis mon blog')
                ->setFrom('ttvdep@gmail.com')
                ->setTo('tuyetrinhvo@gmail.com')
                ->setBody('Contenu du message :' . $post['message'].'<br/>ContactMail :'.$post['email'])
            ;

            $this->get('swiftmailer.mailer')->send($mail);

            $this->addFlash('success', 'Le message a été bien été envoyé.');

            return $this->redirectToRoute('contact');
        }

        return $this->render(
            'contact/contact.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}