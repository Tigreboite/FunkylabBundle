<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

/**
 * @Route("/user")
 */
class UserController extends DatagridController
{
    protected $entityName = 'Tigreboite\FunkylabBundle\Entity\User';
    protected $formType = 'Tigreboite\FunkylabBundle\Form\Type\UserType';
    protected $route_base = 'admin_user';
    protected $repository = 'TigreboiteFunkylabBundle:User';
    protected $dir_path = 'medias/user/';

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:index.html.twig")
     * @Menu("User", icon="fa-user",groupe="CMS")
     * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities.
     *
     * @Route("/list", name="admin_user_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction()
    {
        return parent::listAction();
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function createAction()
    {
        return parent::createAction();
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function newAction()
    {
        return parent::newAction();
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function editAction($id)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }


        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($entity->getPlainPassword()) {
                $entity->setPassword($entity->getPlainPassword());
            }

            $em->flush();

            $event = new EntityEvent($entity);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_CREATED, $event);

            return $this->redirect($this->generateUrl('admin_user'));
        }


        return array(
          'entity' => $entity,
          'form' => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_user_update")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function updateAction($id)
    {
        return parent::updateAction($id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_user_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_user_upload")
     */
    public function uploadAction()
    {
        return parent::uploadAction();
    }

    /**
     * @Route("/autocomplete/request", name="admin_user_autocomplete_request", options={"expose"=true})
     * @Method("GET")
     */
    public function ajaxAction()
    {
        return parent::ajaxAction();
    }

}
