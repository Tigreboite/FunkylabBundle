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
use Tigreboite\FunkylabBundle\Entity\Sortable;
use Tigreboite\FunkylabBundle\Form\SortableType;


/**
 * Sortable controller.
 *
 * @Route("/admin/sortable")
 */
class SortableController extends Controller
{

    protected $formType   = 'SortableType';
    protected $route_base = 'admin_sortable';
    protected $repository = '%bundle_name%:%entity_name%';

    /**
     * Lists all Sortable entities.
     *
     * @Route("/", name="admin_sortable")
     * @Method("GET")
     * @Template()
     * @Menu("Sortable", dataType="string",icon="fa-flag",groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Creates a new Sortable entity.
     *
     * @Route("/", name="admin_sortable_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sortable();
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
     * Displays a form to create a new Sortable entity.
     *
     * @Route("/new", name="admin_sortable_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Sortable();
        $form   = $this->createCreateForm($entity);

        return array(
          'entity' => $entity,
          'form'   => $form->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Displays a form to edit an existing Sortable entity.
     *
     * @Route("/{id}/edit", name="admin_sortable_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sortable entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
          'entity'      => $entity,
          'form'   => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Creates a form to create a Sortable entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sortable $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new SortableType($em), $entity, array(
          'action' => $this->generateUrl($this->route_base.'_create'),
          'method' => 'POST',
          'allow_extra_fields'=>true,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to edit a Sortable entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Sortable $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new SortableType($em), $entity, array(
          'action' => $this->generateUrl('admin_sortable_update', array('id' => $entity->getId())),
          'method' => 'PUT',

        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Sortable entity.
     *
     * @Route("/{id}", name="admin_sortable_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
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
     * Deletes a Sortable entity.
     *
     * @Route("/{id}", name="admin_sortable_delete", options={"expose"=true})
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
     * @Route("/upload", name="admin_sortable_upload")
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

    /**
     * Lists all Platform entities.
     *
     * @Route("/liste", name="admin_sortable_liste")
     * @Method("GET")
     * @Template()
     */
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

    /**
     * Save order
     *
     * @Route("/save/order", name="admin_sortable_order")
     * @Method("POST")
     */
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
