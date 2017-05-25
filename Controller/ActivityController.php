<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\Activity;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Activity controller.
 *
 * @Route("/activity")
 */
class ActivityController extends \Tigreboite\FunkylabBundle\Controller\DatagridController
{
    protected $entityName = 'Tigreboite\FunkylabBundle\Entity\Activity';
    protected $formType = 'Tigreboite\FunkylabBundle\FormActivityType';
    protected $route_base = 'admin_activity';
    protected $repository = 'TigreboiteFunkylabBundle:Activity';
    protected $dir_path = 'medias/AppBundle/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_activity")
     * @Method("GET")
     * @Template()
     * @Menu("Activity", dataType="string",icon="fa-list",groupe="CMS")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities.
     *
     * @Route("/list", name="admin_activity_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_activity_create")
     * @Method("POST")
     * @Template("AdminBundle:Activity:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_activity_new")
     * @Method("GET")
     * @Template("AdminBundle:Activity:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_activity_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("AdminBundle:Activity:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_activity_update")
     * @Template("AdminBundle:Activity:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_activity_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_activity_upload")
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }

    /**
     * @Route("/autocomplete/request", name="admin_activity_autocomplete_request", options={"expose"=true})
     * @Method("GET")
     */
    public function ideaajaxAction(Request $request)
    {
        $query = $request->get('q', '');
        $orderby = $request->get('orderyby', 'title');
        $order = $request->get('order', 'ASC');
        $limit = $request->get('limit', '10');
        $page = $request->get('page', '1') - 1;
        if ($page < 0) {
            $page = 1;
        }

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($this->repository);
        $entities = $repo->findDataQuery($query, $orderby, $order, $limit, $page);
        $items = array();

        foreach ($entities as $entity) {
            $items[] = array(
                'id' => $entity->getId(),
                'title' => $entity->getTitle(),
                'summary' => $entity->getSummary(),
            );
        }

        $data = array(
            'q' => $query,
            'term' => $query,
            'page' => $page + 1,
            'items' => $items,
            'total_count' => $repo->countDataQuery($query),
        );

        return new JsonResponse($data);
    }
}
