<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\BlogType;
use Tigreboite\FunkylabBundle\Form\BlogTypeType;

/**
 * BlogType controller.
 *
 * @Route("/blogtype")
 */
class BlogTypeController extends Controller
{
    /**
     * Lists all BlogType entities.
     *
     * @Route("/", name="admin_blogtype")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    /**
     * Lists all.
     *
     * @Route("/list", name="admin_blogtype_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Not found');
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
        $entities = $em->getRepository('TigreboiteFunkylabBundle:BlogType')
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
     * Creates a new BlogType entity.
     *
     * @Route("/", name="admin_blogtype_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:BlogType:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlogType();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_blogtype'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to create a BlogType entity.
     *
     * @param BlogType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlogType $entity)
    {
        $form = $this->createForm(new BlogTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_blogtype_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlogType entity.
     *
     * @Route("/new", name="admin_blogtype_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:BlogType:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new BlogType();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Displays a form to edit an existing BlogType entity.
     *
     * @Route("/{id}/edit", name="admin_blogtype_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:BlogType:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogType entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to edit a BlogType entity.
     *
     * @param BlogType $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(BlogType $entity)
    {
        $form = $this->createForm(new BlogTypeType(), $entity, array(
            'action' => $this->generateUrl('admin_blogtype_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing BlogType entity.
     *
     * @Route("/{id}", name="admin_blogtype_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:BlogType:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogType entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_blogtype_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }
    /**
     * Deletes  entity.
     *
     * @Route("/{id}", name="admin_blogtype_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogType')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogType entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }
}
