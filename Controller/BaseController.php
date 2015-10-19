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

class BaseController extends Controller
{
    protected $entityName = 'Base';
    protected $formType   = 'BaseType';
    protected $route_base = 'admin_Base';
    protected $repository = 'Tigreboite:Base';
    protected $dir_path   = 'medias/Base/';

    public function indexAction()
    {
        return array();
    }

    public function createAction(Request $request)
    {
        $entity = new $this->entityName();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl($this->route_base));
        }

        return array(
          'entity' => $entity,
          'form'   => $form->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    public function newAction(Request $request)
    {
        $entity = new $this->entityName();
        $form   = $this->createCreateForm($entity);

        return array(
          'entity' => $entity,
          'form'   => $form->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Base entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
          'entity'      => $entity,
          'form'   => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    public function createCreateForm($entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new $this->formType($em), $entity, array(
          'action' => $this->generateUrl($this->route_base.'_create'),
          'method' => 'POST',
          'allow_extra_fields'=>true,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function createEditForm($entity)
    {

        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new $this->formType($em), $entity, array(
          'action' => $this->generateUrl($this->route_base.'_update', array('id' => $entity->getId())),
          'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl($this->route_base.'_edit', array('id' => $id)));
        }

        return array(
          'entity'      => $entity,
          'form'   => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }

    public function uploadAction(Request $request)
    {
        $data = array('success'=>false);
        $uploadedFile = $request->files->get('file');

        if ($uploadedFile)
        {
            $file = $uploadedFile->move('../web/'.$this->dir_path, $uploadedFile->getClientOriginalName());
            if($file)
            {
                $data = array(
                  'success'=>true,
                  'filename'=>$uploadedFile->getClientOriginalName(),
                  'path'=>$this->dir_path.$uploadedFile->getClientOriginalName()
                );
            }
        }

        return new JsonResponse($data);
    }

}
