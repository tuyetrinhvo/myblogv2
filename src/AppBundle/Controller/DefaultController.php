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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class DefaultController
 *
 * @category PHP_Class
 * @package  AppBundle\Controller
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  tuyetrinhvo@2018
 * @link     Link Name
 */
class DefaultController extends Controller
{
    /**
     * Function indexAction
     *
     * @Route("/",      name="homepage")
     * @Method({"GET"})
     * @return          \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}