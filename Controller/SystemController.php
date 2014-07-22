<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Scoring controller.
 *
 * @Route("/system")
 */
class SystemController extends Controller
{
    /**
     * @Route("/files", name="funkylab_system_files")
     * @Template()
     */
    public function filesAction()
    {
        return array();
    }

    /**
     * @Route("/manager", name="funkylab_system_manager")
     * @Template()
     */
    public function managerAction()
    {
        return array();
    }

    /**
     * @Route("/languages", name="funkylab_system_languages")
     * @Template()
     */
    public function languagesAction()
    {
        return array();
    }

    /**
     * @Route("/users", name="funkylab_system_users")
     * @Template()
     */
    public function usersAction()
    {
        return array();
    }
}
