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
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tigreboite\FunkylabBundle\Entity\User;

/**
 * @Route("/user")
 */
class UserController extends DatagridController
{
    protected $entityName = 'Tigreboite\FunkylabBundle\Entity\User';
    protected $formType = 'Tigreboite\FunkylabBundle\Form\UserType';
    protected $route_base = 'admin_user';
    protected $repository = 'TigreboiteFunkylabBundle:User';
    protected $dir_path = 'medias/user/';

    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_user")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:index.html.twig")
     * @Menu("User", dataType="string",icon="fa-user",groupe="CMS")
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
    public function listAction(Request $request)
    {
        return parent::listAction($request);
    }

    /**
     * Create entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * Displays a form to create a new entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function newAction(Request $request)
    {
        return parent::newAction($request);
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function editAction(Request $request,  $id)
    {
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
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    /**
     * Delete an entity.
     *
     * @Route("/{id}", name="admin_user_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        return parent::deleteAction($request, $id);
    }

    /**
     * Upload files.
     *
     * @Route("/upload", name="admin_user_upload")
     */
    public function uploadAction(Request $request)
    {
        return parent::uploadAction($request);
    }

    /**
     * @Route("/autocomplete/request", name="admin_user_autocomplete_request", options={"expose"=true})
     * @Method("GET")
     */
    public function ajaxAction(Request $request)
    {
        return parent::ajaxAction($request);
    }

    /**
     * Export EXCEL idea list.
     *
     * @Route("/download_excel", name="admin_user_export")
     */
    public function exportCSVAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $iterableResult = $em->getRepository('TigreboiteFunkylabBundle:User')
          ->createQueryBuilder('a')
          ->getQuery()
          ->iterate();

        $handle = fopen('php://memory', 'r+');
        $header = array();

        fputcsv($handle, array(
          'ref cust',
          'Email',
          'civility',
          'firstname',
          'last name',
          'dob',
          'adresse',
          'adresse 2',
          'zip code',
          'city',
          'enable',
          'roles',
          'Country ID',
          'Fav sport 1',
          'Fav sport 2',
          'Fav sport 3',
          'Language ID',
          'decathlon card id',
          'NB points current',
          'NB points total',
          'Newsletter',
          'Newsletter partner',
          'id facebook',
          'id twitter',
          'created at',
          'last login',
          'cgu',
          'id decathlon',
          'nombre d\'idees deposees',
          'nombre de vote sur des idees',
          'nombre total de participations au global',
          'nombre de participation a un questionnaire sur projet',
          'nombre de vote sur un questionnaire',
          'nombre de commentaires au global',
          'nombre de commentaires sur les idees',
          'nombre de commentaires sur le blog',
        ), ';');

        while (false !== ($row = $iterableResult->next())) {
            $data = $row[0]->getCSVData($em);

            fputcsv($handle, $data, ';');
            $em->detach($row[0]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
          'Content-Type' => 'application/force-download',
          'Content-Disposition' => 'attachment; filename="export-users.csv"',
        ));
    }
}
