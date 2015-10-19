<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015
 */
namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class SimpleformController extends BaseController
{

    protected $entityName = 'Simpleform';
    protected $formType   = 'SimpleformType';
    protected $route_base = 'admin_simpleform';
    protected $repository = 'Tigreboite:Simpleform';
    protected $dir_path   = 'medias/simpleform/';

    /**
     * Display a simpleform
     * @return array
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $em      = $this->getDoctrine()->getManager();
        $repo    = $em->getRepository($this->repository);
        $entity  = $repo->findOneBy(array(), array('id' => 'DESC'));

        if (!$entity) {
            $entity   = new $this->entityName();
            $editForm = $this->createCreateForm($entity);
        } else {
            $editForm = $this->createEditForm($entity);
        }

        return array('entity' => $entity, 'form' => $editForm->createView(), 'ajax' => $request->isXmlHttpRequest());

    }

}
