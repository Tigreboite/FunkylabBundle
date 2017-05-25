<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Abuse controller.
 *
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * Lists all Abuse entities.
     *
     * @Route("/list", name="admin_media_list", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $request = $this->get('request');
        $dir = $request->get('dir');
        $dir_image = 'medias/'.$dir;
        $data = array();

        $path = $request->getBasePath()."/".$dir_image;

        foreach (glob($path.'/*.*') as $filename) {
            $data[] = '/'.$dir_image.'/'.basename($filename);
        }

        return new JsonResponse($data);
    }

    /**
     * Lists all Abuse entities.
     *
     * @Route("/delete", name="admin_media_delete", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function deleteAction()
    {
        $request = $this->get('request');
        $dir = $request->get('dir', false);
        $src = $request->get('src', false);

        if (!$dir || !$src) {
            throw $this->createNotFoundException('bad request');
        }

        $path = realpath(dirname(__FILE__).'/../../../../web');

        if (file_exists($path.$src)) {
            unlink($path.$src);
            $data = array('success' => true);
        } else {
            $data = array('success' => false);
        }

        return new JsonResponse($data);
    }
}
