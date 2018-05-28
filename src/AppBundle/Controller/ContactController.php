<?php
/**
 * Class Doc Controller
 *
 * PHP version 7.0
 *
 * @category PHP_Class
 * @package  AppBundle
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\ContactType;

/**
 * Class ContactController
 *
 * @category PHP_Class
 * @package  AppBundle\Controller
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
class ContactController extends Controller
{
    /**
     * Function contactAction
     *
     * @param Request       $request Some argument description
     * @param \Swift_Mailer $mailer  Some argument description
     *
     * @Route("/contact", name="contact")
     * @Method({"GET",    "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function contactAction(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $mail = \Swift_Message::newInstance()
                ->setSubject('Message depuis mon blog')
                ->setFrom('noreply@ttvo.fr')
                ->setTo('tuyetrinhvo@gmail.com')
                ->setBody(
                    'Contenu du message : ' . $data['message'].
                    '<br/>Message envoyé par : '.$data['nom'].
                    '<br/> Son adresse email : '.$data['email']
                )
                ->setContentType('text/html');

            if ($mailer->send($mail)) {
                $this->addFlash('success', 'Le message a été bien envoyé.');

                return $this->redirectToRoute('contact');
            }

            $this->addFlash('error', 'Le message n\'a pas été envoyé.');

            return $this->redirectToRoute('contact');
        }

        return $this->render(
            'contact/contact.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
