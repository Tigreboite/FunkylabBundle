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
use Symfony\Component\HttpFoundation\Response;

/**
 * Actuality controller.
 *
 * @Route("/admin/actuality")
 */
class ActualityController extends DatagridController
{
    protected $entityName = 'Tigreboite\FunkylabBundle\Entity\Actuality';
    protected $formType = 'Tigreboite\FunkylabBundle\Form\Type\ActualityType';
    protected $route_base = 'admin_actuality';
    protected $repository = 'AppBundle:Actuality';
    protected $dir_path = 'medias/news/';

    /**
     * Lists all entities.
     *
     * @Route("/", name="admin_actuality")
     * @Method("GET")
     * @Template()
     * @Menu("Actualités", icon="fa-flag", groupe="Nexity", order=1)
     * @Security("has_role('ROLE_ETUDE')")
     */
    public function indexAction()
    {
        return parent::indexAction();
    }

    /**
     * Get all entities.
     *
     * @Route("/list", name="admin_actuality_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_actuality_create")
     * @Method("POST")
     * @Template("AdminBundle:Actuality:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_actuality_new")
     * @Method("GET")
     * @Template("AdminBundle:Actuality:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_actuality_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("AdminBundle:Actuality:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        return parent::editAction($request, $id);
    }

    /**
     * Edits an existing entity.
     *
     * @Route("/update/{id}", name="admin_actuality_update")
     * @Method("PUT")
     * @Template("AdminBundle:Actuality:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_actuality_delete", options={"expose"=true})
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

        //Check and remove if actuality is on home
        $homeManager = $this->get('appbundle.manager.home');
        $home = $homeManager->findOneBy(array(), array('id' => 'DESC'));
        if ($home) {
            $actu = $home->getActuality();
            if ($actu && $actu->getId() == $entity->getId()) {
                $home->setActuality(null);
                $em->persist($home);
            }
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_actuality_upload")
     * @Method({"POST","PUT"})
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }

    /**
     * @Route("/autocomplete/request", name="admin_actuality_autocomplete_request", options={"expose"=true})
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