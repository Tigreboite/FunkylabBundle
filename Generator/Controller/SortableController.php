<?php
/**
 * Generated with funkylab
 */

namespace Tigreboite\FunkylabBundle\Generator\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Annotation\Menu;

/**
 * Sortable controller.
 *
 * @Route("/admin/sortable")
 */
class SortableController extends \Tigreboite\FunkylabBundle\Controller\SortableController
{
    protected $entityName = '%bundle_name%\Entity\Sortable';
    protected $formType = '%bundle_name%\Form\SortableType';
    protected $route_base = 'admin_sortable';
    protected $repository = '%bundle_name%:%entity_name%';
    protected $dir_path = 'medias/%bundle_name%/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_sortable")
     * @Method("GET")
     * @Template()
     * @Menu("Sortable", icon="fa-flag", groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Creates a new entity.
     *
     * @Route("/", name="admin_sortable_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function createAction()
    {
        return parent::createAction();
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_sortable_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function newAction()
    {
        return parent::newAction();
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_sortable_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function editAction($id)
    {
        return parent::editAction($id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_sortable_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function updateAction($id)
    {
        return parent::updateAction(id);
    }

    /**
     * Deletes an entity.
     *
     * @Route("/{id}", name="admin_sortable_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_sortable_upload")
     * @Method({"POST","PUT"})
     */
    public function uploadAction()
    {
        return parent::uploadAction();
    }

    /**
     * Get a list of all entities.
     *
     * @Route("/liste", name="admin_sortable_liste")
     * @Method("GET")
     * @Template()
     */
    public function listeAction()
    {
        return parent::listeAction();
    }

    /**
     * Save order.
     *
     * @Route("/save/order", name="admin_sortable_order")
     * @Method("POST")
     */
    public function orderAction()
    {
        return parent::orderAction();
    }

    /**
     * Creates a form to create a entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm($entity)
    {
        return parent::createCreateForm($entity);
    }

    /**
     * Creates a form to edit a entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createEditForm($entity)
    {
        return parent::createEditForm($entity);
    }
}
