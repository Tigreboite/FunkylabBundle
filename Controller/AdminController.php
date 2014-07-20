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
        return array();
    }

    /**
     * @Route("/login", name="funkylab_login")
     * @Template()
     */
    public function loginAction()
    {
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
        $logout = $this->generateUrl('fos_user_security_logout');
        return $this->redirect($logout);
    }
}
