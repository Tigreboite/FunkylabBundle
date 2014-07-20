<?php

namespace CyrilPereira\FunkylabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
        return array();
    }

    /**
     * @Route("/logout")
     * @Template()
     */
    public function logoutAction()
    {
        return array();
    }
}
