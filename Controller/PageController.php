<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015.
 */

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tigreboite\FunkylabBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller.
 *
 * @Route("/admin/page")
 */
class PageController extends DatagridController
{
    protected $entityName = 'Tigreboite\FunkylabBundle\Entity\Page';
    protected $formType = 'Tigreboite\FunkylabBundle\Form\Type\PageType';
    protected $route_base = 'admin_nexity_page';
    protected $repository = 'AppBundle:Page';
    protected $dir_path = 'medias/page/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_nexity_page")
     * @Method("GET")
     * @Template()
     * @Menu("Pages", icon="fa-flag",groupe="Nexity", order=5)
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities.
     *
     * @Route("/list", name="admin_nexity_page_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_nexity_page_create")
     * @Method("POST")
     * @Template("AdminBundle:Page:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_nexity_page_new")
     * @Method("GET")
     * @Template("AdminBundle:Page:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_nexity_page_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("AdminBundle:Page:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_nexity_page_update")
     * @Method("PUT")
     * @Template("AdminBundle:Page:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_nexity_page_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        //Removed blocs
        $blocs = $entity->getBlocs();
        foreach ($blocs as $bloc) {
            $em->remove($bloc);
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_nexity_page_upload")
     * @Method({"POST","PUT"})
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }

    /**
     * @Route("/autocomplete/request", name="admin_nexity_page_autocomplete_request", options={"expose"=true})
     * @Method("GET")
     */
    public function ajaxAction(Request $request)
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
