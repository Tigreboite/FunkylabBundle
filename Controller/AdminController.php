<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Route("/", name="funkylab_home")
     * @Template()
     */
    public function indexAction()
    {
        $logger = $this->get('logger');
        $logger->info('Funkylab : Home');
        return array();
    }

    /**
     * @Route("/about", name="funkylab_about")
     * @Template()
     */
    public function aboutAction()
    {
        $logger = $this->get('logger');
        $logger->info('Funkylab : About');
        return array();
    }

    /**
     * @Route("/login", name="funkylab_login")
     * @Template()
     */
    public function loginAction()
    {
        $logger = $this->get('logger');
        $logger->info('Funkylab : call Login form');
        return $this->render('TigreboiteFunkylabBundle:Admin:login.html.twig', array(
          'csrf_token' => $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
        ));
    }

    /**
     * @Route("/logout")
     * @Template()
     */
    public function logoutAction()
    {
        $logger = $this->get('logger');
        $logger->info('Funkylab : call Logout');
        $logout = $this->generateUrl('fos_user_security_logout');
        return $this->redirect($logout);
    }
}
