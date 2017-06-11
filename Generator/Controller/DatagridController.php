<?php
/**
 * Generated with funkylab
 */

namespace Tigreboite\FunkylabBundle\Generator\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Tigreboite\FunkylabBundle\Controller\DatagridController as BaseController;

/**
 * Datagrid controller.
 *
 * @Route("/admin/datagrid")
 */
class DatagridController extends BaseController
{
    protected $entityName = '%bundle_name%\Entity\Datagrid';
    protected $formType = '%bundle_name%\Form\Type\DatagridType';
    protected $route_base = 'admin_datagrid';
    protected $repository = '%bundle_name%:%entity_name%';
    protected $dir_path = 'medias/%bundle_name%/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_datagrid")
     * @Method("GET")
     * @Template()
     * @Menu("Datagrid", icon="fa-flag" ,groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities.
     *
     * @Route("/list", name="admin_datagrid_list", options={"expose"=true})
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return parent::listAction();
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_datagrid_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction()
    {
        return parent::createAction();
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_datagrid_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     * @return array
     */
    public function newAction()
    {
        return parent::newAction();
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_datagrid_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     * @param $id
     * @return array
     */
    public function editAction($id)
    {
        return parent::editAction($id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_datagrid_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Datagrid:form.html.twig")
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($id)
    {
        return parent::updateAction($id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_datagrid_delete", options={"expose"=true})
     * @Method("DELETE")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        return parent::deleteAction($id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_datagrid_upload")
     * @Method({"POST","PUT"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function uploadAction()
    {
        return parent::uploadAction();
    }
}
