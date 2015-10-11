<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015
 */
namespace Tigreboite\FunkylabBundle\Generator\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tigreboite\FunkylabBundle\Entity\Datagrid;
use Tigreboite\FunkylabBundle\Form\DatagridType;


/**
 * Datagrid controller.
 *
 * @Route("/admin/datagrid")
 */
class DatagridController extends Controller
{

    protected $formType   = 'DatagridType';
    protected $route_base = 'admin_datagrid';
    protected $repository = '%bundle_name%:%entity_name%';

    /**
     * Lists all Datagrid entities.
     *
     * @Route("/", name="admin_datagrid")
     * @Method("GET")
     * @Template()
     * @Menu("Datagrid", dataType="string",icon="fa-flag",groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all Datagrid entities.
     *
     * @Route("/list", name="admin_datagrid_list", options={"expose"=true})
     * @Method("GET")
     */
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

    /**
     * Creates a new Datagrid entity.
     *
     * @Route("/", name="admin_datagrid_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Datagrid();
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

    /**
     * Displays a form to create a new Datagrid entity.
     *
     * @Route("/new", name="admin_datagrid_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Datagrid();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Displays a form to edit an existing Datagrid entity.
     *
     * @Route("/{id}/edit", name="admin_datagrid_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
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

    /**
     * Creates a form to create a Datagrid entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Datagrid $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new DatagridType($em), $entity, array(
          'action' => $this->generateUrl($this->route_base.'_create'),
          'method' => 'POST',
          'allow_extra_fields'=>true,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
    * Creates a form to edit a Datagrid entity.
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Datagrid $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new DatagridType($em), $entity, array(
            'action' => $this->generateUrl('admin_datagrid_update', array('id' => $entity->getId())),
            'method' => 'PUT',

        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Datagrid entity.
     *
     * @Route("/{id}", name="admin_datagrid_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
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
    /**
     * Deletes a Datagrid entity.
     *
     * @Route("/{id}", name="admin_datagrid_delete", options={"expose"=true})
     * @Method("DELETE")
     */
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

    /**
     * Upload files
     *
     * @Route("/upload", name="admin_datagrid_upload")
     */
    public function uploadAction(Request $request)
    {
        $dir_path = 'medias/%entity_path_file%/';
        $data = array('success'=>false);
        $uploadedFile = $request->files->get('file');

        if ($uploadedFile)
        {
            $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
            if($file)
            {
                $data = array(
                  'success'=>true,
                  'filename'=>$uploadedFile->getClientOriginalName(),
                  'path'=>$dir_path.$uploadedFile->getClientOriginalName()
                );
            }
        }

        return new JsonResponse($data);
    }

}
