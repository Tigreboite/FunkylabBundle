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

class DatagridController extends Controller
{

    protected $entityName = 'Datagrid';
    protected $formType   = 'DatagridType';
    protected $route_base = 'admin_datagrid';
    protected $repository = 'Tigreboite:Datagrid';
    protected $dir_path   = 'medias/datagrid/';

    public function indexAction()
    {
        return array();
    }

    public function listAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException("Not found");
        }

        // GET
        $draw = $request->query->get('draw');
        $start = $request->query->get('start');
        $length = $request->query->get('length');
        $search_string = $request->query->get('search');
        $search_string = $search_string['value'];
        $order_column = $request->query->get('order');
        $order_column = $order_column[0]['column'];
        $order_dir = $request->query->get('order');
        $order_dir = $order_dir[0]['dir'];
        $columns = $request->query->get('columns');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository($this->repository)
          ->findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir);

        $serializer = $this->get('jms_serializer');

        // Construct JSON
        $data_to_return = array();
        $data_to_return['draw'] = $draw;
        $data_to_return['recordsTotal'] = $entities['count_all'];
        $data_to_return['recordsFiltered'] = $entities['count_filtered'];

        unset($entities['count_all']);
        unset($entities['count_filtered']);
        $data_to_return['data'] = $entities;

        return new Response($serializer->serialize($data_to_return, 'json'));
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
            throw $this->createNotFoundException('Unable to find Datagrid entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
          'entity'      => $entity,
          'form'   => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    private function createCreateForm($entity)
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

    private function createEditForm($entity)
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
