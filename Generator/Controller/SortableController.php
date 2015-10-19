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
class SortableController extends \Tigreboite\FunkylabBundle\Controller\SortableController
{

    protected $entityName = '%bundle_name%\Entity\Sortable';
    protected $formType   = '%bundle_name%\Form\SortableType';
    protected $route_base = 'admin_sortable';
    protected $repository = '%bundle_name%:%entity_name%';
    protected $dir_path   = 'medias/%bundle_name%/';

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
        return parent::indexAction();
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
        return parent::createAction($request);
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
        return parent::newAction($request);
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
        return parent::editAction($request, $id);
    }

    /**
     * Creates a form to create a Sortable entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createCreateForm($entity)
    {
        return parent::createCreateForm($entity);
    }

    /**
     * Creates a form to edit a Sortable entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createEditForm($entity)
    {
        return parent::createEditForm($entity);
    }

    /**
     * Edits an existing Sortable entity.
     *
     {id}", name="admin_sortable_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Sortable:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }
    /**
     * Deletes a Sortable entity.
     *
     * @Route("/{id}", name="admin_sortable_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Upload files
     *
     * @Route("/upload", name="admin_sortable_upload")
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
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
        return parent::listeAction();
    }

    /**
     * Save order
     *
     * @Route("/save/order", name="admin_sortable_order")
     * @Method("POST")
     */
    public function orderAction()
    {
        return parent::orderAction();
    }


}
