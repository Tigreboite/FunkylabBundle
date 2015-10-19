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

class SortableController extends BaseController
{

    protected $entityName = 'Simpleform';
    protected $formType   = 'SimpleformType';
    protected $route_base = 'admin_simpleform';
    protected $repository = 'Tigreboite:Simpleform';
    protected $dir_path   = 'medias/simpleform/';

    public function listeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities  = $em->getRepository($this->repository)->findBy(
          array(),
          array('ordre'=>'ASC')
        );

        return array(
          'entities' => $entities,
        );
    }

    public function orderAction()
    {
        $request    = $this->get('request');
        $type       = $request->get('type',false);
        $platform   = $request->get('platform',false);
        $items      = $request->get('item', array());

        $data = array();
        $data['type'] = $type;
        $data['platform'] = $platform;
        $data['items'] = $items;

        $em = $this->getDoctrine()->getManager();

        $count = 1;
        foreach($items as $id)
        {
            $entity = $em->getRepository($this->repository)->find($id);
            $entity->setOrdre($count);
            $em->persist($entity);
            $count++;
        }

        $em->flush();
        return new JsonResponse($data);
    }


}
