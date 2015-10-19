<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015
 */
namespace Tigreboite\FunkylabBundle\Generator\Controller;
use Symfony\Component\HttpFoundation\Request;
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
class DatagridController extends \Tigreboite\FunkylabBundle\Controller\DatagridController
{
    protected $entityName = '%bundle_name%\Entity\Datagrid';
    protected $formType   = '%bundle_name%\Form\DatagridType';
    protected $route_base = 'admin_datagrid';
    protected $repository = '%bundle_name%:%entity_name%';
    protected $dir_path   = 'medias/%bundle_name%/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_datagrid")
     * @Method("GET")
     * @Template()
     * @Menu("Datagrid", dataType="string",icon="fa-flag",groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities
     *
     * @Route("/list", name="admin_datagrid_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * Create entity
     *
     * @Route("/", name="admin_datagrid_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_datagrid_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_datagrid_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_datagrid_update")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_datagrid_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Upload files
     *
     * @Route("/upload", name="admin_datagrid_upload")
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }

}
