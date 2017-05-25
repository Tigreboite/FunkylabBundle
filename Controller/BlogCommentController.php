<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\BlogComment;
use Tigreboite\FunkylabBundle\Form\BlogCommentType;

/**
 * BlogComment controller.
 *
 * @Route("/blogcomment")
 */
class BlogCommentController extends Controller
{

    /**
     * Lists all BlogComment entities.
     *
     * @Route("/{blog_id}", name="admin_blogcomment", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($blog_id)
    {
        return array(
            'blog_id' => $blog_id
        );
    }

    /**
     * Lists all.
     *
     * @Route("/list/{blog_id}", name="admin_blogcomment_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request, $blog_id)
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
        $entities = $em->getRepository('TigreboiteFunkylabBundle:BlogComment')
            ->findDataTableBlogComment($columns, $start, $length, $search_string, $order_column, $order_dir, $blog_id);

        $serializer = $this->get('jms_serializer');

        foreach ($entities as $k => $v) {
            if (is_array($v))
                $entities[$k] = array_map("htmlentities", $v);
        }

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
     * Creates a new BlogComment entity.
     *
     * @Route("/", name="admin_blogcomment_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:BlogComment:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BlogComment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_blogcomment'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Creates a form to create a BlogComment entity.
     *
     * @param BlogComment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(BlogComment $entity)
    {
        $form = $this->createForm(new BlogCommentType(), $entity, array(
            'action' => $this->generateUrl('admin_blogcomment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BlogComment entity.
     *
     * @Route("/new", name="admin_blogcomment_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:BlogComment:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new BlogComment();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Displays a form to edit an existing BlogComment entity.
     *
     * @Route("/{id}/edit", name="admin_blogcomment_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:BlogComment:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogComment entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Creates a form to edit a BlogComment entity.
     *
     * @param BlogComment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(BlogComment $entity)
    {
        $form = $this->createForm(new BlogCommentType(), $entity, array(
            'action' => $this->generateUrl('admin_blogcomment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing BlogComment entity.
     *
     * @Route("/{id}", name="admin_blogcomment_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:BlogComment:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogComment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_blogcomment_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Deletes  entity.
     *
     * @Route("/{id}", name="admin_blogcomment_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:BlogComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BlogComment entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }
}
