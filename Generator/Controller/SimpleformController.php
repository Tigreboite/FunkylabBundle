<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace Tigreboite\FunkylabBundle\Generator\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Tigreboite\FunkylabBundle\Entity\Simpleform;

/**
 * Simpleform controller.
 *
 * @Route("/admin/simpleform")
 */
class SimpleformController extends \Tigreboite\FunkylabBundle\Controller\SimpleformController
{
    protected $entityName = '%bundle_name%\Entity\Simpleform';
    protected $formType = '%bundle_name%\Form\SimpleformType';
    protected $route_base = 'admin_simpleform';
    protected $repository = '%bundle_name%:%entity_name%';
    protected $dir_path = 'medias/%bundle_name%/';

    /**
     * Display a form to edit current entity.
     *
     * @Route("/", name="admin_simpleform")
     * @Method("GET")
     * @Template()
     * @Menu("Simpleform", dataType="string",icon="fa-flag",groupe="CMS")
     * %security_roles%
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Creates a new entity.
     *
     * @Route("/", name="admin_simpleform_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Simpleform:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_simpleform_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Simpleform:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_simpleform_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Simpleform:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_simpleform_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Simpleform:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_simpleform_upload")
     * @Method({"POST","PUT"})
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }
}
