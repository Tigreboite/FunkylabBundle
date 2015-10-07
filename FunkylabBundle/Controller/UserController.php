<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tigreboite\FunkylabBundle\Entity\User;
use Tigreboite\FunkylabBundle\Form\UserType;
use Tigreboite\FunkylabBundle\Tools;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

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
        return array();
    }

    /**
     * Lists all User entities.
     *
     * @Route("/list", name="admin_user_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException("Not found");
        }

        // GET
        $draw = $request->query->get('draw');
        $start = $request->query->get('start');
        $length = $request->query->get('length');
        $search_string = $request->query->get('search');
        $search_string = $search_string['value'];
        $order_column = $request->query->get('order');
        $order_column = $order_column[0]['column'];
        $order_dir = $request->query->get('order');
        $order_dir = $order_dir[0]['dir'];
        $columns = $request->query->get('columns');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('TigreboiteFunkylabBundle:User')
            ->findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir);

        $serializer = $this->get('jms_serializer');

        // Construct JSON
        $data_to_return = array();
        $data_to_return['draw'] = $draw;
        $data_to_return['recordsTotal'] = $entities['count_all'];
        $data_to_return['recordsFiltered'] = $entities['count_filtered'];

        unset($entities['count_all']);
        unset($entities['count_filtered']);
        $data_to_return['data'] = $entities;

        return new Response($serializer->serialize($data_to_return, 'json'));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setRefCust($em->getRepository("TigreboiteFunkylabBundle:User")->generateUniqueRefCust());
            $this->HowItWorkDisplay($entity);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
            'error'=>Tools::getErrorMessages($form)
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType($this->container), $entity, array(
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType($this->container), $entity, array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="admin_user_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:User:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setPassword($entity->getPlainPassword());

            $this->HowItWorkDisplay($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest()
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="admin_user_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }
    /**
     * Archive a User entity.
     *
     * @Route("/archive/{id}", name="admin_user_archive", options={"expose"=true})
     */
    public function archiveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $entity->setIsarchived(true);
        $em->persist($entity);
        $em->flush();

        return new Response('Archived');
    }
    /**
     * UnArchive a User entity.
     *
     * @Route("/unarchive/{id}", name="admin_user_unarchive", options={"expose"=true})
     */
    public function unarchiveAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $entity->setIsarchived(false);
        $em->persist($entity);
        $em->flush();

        return new Response('UnArchived');
    }

    private function HowItWorkDisplay($entity)
    {
        if ($entity->getHowItWorkDisplay()) {
            $em = $this->getDoctrine()->getManager();
            $howit_display = $em->getRepository('TigreboiteFunkylabBundle:User')->findOneBy(array('howItWorkDisplay' => true));
            
            if ($howit_display) {
                $howit_display->setHowItWorkDisplay(false);
                $em->persist($howit_display);
                $em->flush();
            }
        }
    }

    /**
     * Export EXCEL idea list
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
            'Content-Disposition' => 'attachment; filename="export-users.csv"'
        ));
    }
}
