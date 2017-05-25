<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\Blog;
use Tigreboite\FunkylabBundle\Form\Type\BlogType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Blog controller.
 *
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * Lists all Blog entities.
     *
     * @Route("/", name="admin_blog")
     * @Method("GET")
     * @Template()
     * @Menu("Blog", dataType="string",icon="fa-book",groupe="Content")
     * @Security("has_role('ROLE_MODERATOR') || has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/redirect/{id}", name="admin_blog_redirection_front", options={"expose"=true})
     */
    public function redirectToFrontAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Blog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        return $this->redirect(
            $this->generateUrl('blog', array(
                'slug' => $entity->getSlug(),
            ))
        );
    }

    /**
     * Lists all.
     *
     * @Route("/list", name="admin_blog_list", options={"expose"=true})
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
        $entities = $em->getRepository('TigreboiteFunkylabBundle:Blog')
            ->findDataTableBlog($columns, $start, $length, $search_string, $order_column, $order_dir);

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
     * Creates a new Blog entity.
     *
     * @Route("/", name="admin_blog_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Blog:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Blog();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($form->get('datePublished')->getData() > new \DateTime('now')) {
                $entity->setStatus($entity::STATUS_UNPUBLISHED);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_blog'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to create a Blog entity.
     *
     * @param Blog $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Blog $entity)
    {
        $form = $this->createForm(new BlogType(), $entity, array(
            'action' => $this->generateUrl('admin_blog_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Blog entity.
     *
     * @Route("/new", name="admin_blog_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Blog:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Blog();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Displays a form to edit an existing Blog entity.
     *
     * @Route("/{id}/edit", name="admin_blog_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Blog:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Blog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to edit a Blog entity.
     *
     * @param Blog $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Blog $entity)
    {
        $form = $this->createForm(new BlogType(), $entity, array(
            'action' => $this->generateUrl('admin_blog_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Blog entity.
     *
     * @Route("/{id}", name="admin_blog_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Blog:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$entity = $em->getRepository('TigreboiteFunkylabBundle:Blog')->find($id)) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($entity->getDatePublished() > new \DateTime('now')) {
                $entity->setStatus($entity::STATUS_UNPUBLISHED);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('admin_blog_edit', array('id' => $id)));
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
     * @Route("/{id}", name="admin_blog_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:Blog')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Blog entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }

    /**
     * Upload files.
     *
     * @Route("/upload/image", name="admin_blog_upload")
     */
    public function uploadAction(Request $request)
    {
        $dir_path = 'medias/blog/';
        $data = array('success' => false);
        $uploadedFile = $request->files->get('images');

        if ($uploadedFile) {
            $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
            if ($file) {
                $data = array(
                    'success' => true,
                    'filename' => $uploadedFile->getClientOriginalName(),
                    'path' => $dir_path.$uploadedFile->getClientOriginalName(),
                );
            }
        }

        return new JsonResponse($data);
    }
}
