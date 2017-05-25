<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/login", name="funkylab_login")
     * @Template()
     */
    public function loginAction()
    {
        $logger = $this->get('logger');
        $logger->info('Funkylab : call Login form');

        return $this->render('TigreboiteFunkylabBundle:Admin:login.html.twig', array(
            'csrf_token' => $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate'),
        ));
    }

    /**
     * @Route("/login_check", name="funkylab_login_check")
     * @Method({"GET","POST"})
     */
    public function loginCheckAction()
    {
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

    /**
     * @Route("/upload_image_wysiwyg", name="upload_image_wysiwyg", options={"expose"=true})
     */
    public function uploadImageWYSIWYGAction(Request $request)
    {
        $extensions = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

        $dir_path = 'medias/wysiwyg_files/';

        $uploadedFile = $request->files->get('file');
        $response = new \StdClass();

        if ($uploadedFile) {
            $ext = $uploadedFile->getClientOriginalExtension();
            if (in_array($ext, $extensions)) {
                $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
                if ($file) {
                    // Generate response.
                    $response->link = '/'.$dir_path.$uploadedFile->getClientOriginalName();
                }
            }
        }

        return new JsonResponse($response);
    }

    private function getLoggedUser()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $user && $user != 'anon.' ? $user : false;
    }
}
