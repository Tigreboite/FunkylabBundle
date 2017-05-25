<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\Pays;
use Tigreboite\FunkylabBundle\Form\PaysType;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Pays controller.
 *
 * @Route("/pays")
 */
class PaysController extends Translator
{
    /**
     * Lists all Pays entities.
     *
     * @Route("/", name="admin_pays")
     * @Method("GET")
     * @Template()
     * @Menu("Countries", dataType="string",icon="fa-globe",groupe="CMS")
     * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
     */
    public function indexAction()
    {
        return array();
    }
    /**
     * Lists.
     *
     * @Route("/list", name="admin_pays_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $t = $this->container->get('translator');
        $locale = $t->getLocale();

        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Not found');
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
        $entities = $em->getRepository('TigreboiteFunkylabBundle:Pays')
            ->findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir, array(), $locale);

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
     * Creates a new Pays entity.
     *
     * @Route("/", name="admin_pays_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Pays:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository('TigreboiteFunkylabBundle:Language')->findAll();

        $entity = new Pays();
        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->updateLanguageEntity($entity);

            return $this->redirect($this->generateUrl('admin_pays'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
            'languages' => $languages,
        );
    }

    /**
     * Creates a form to create a Pays entity.
     *
     * @param Pays $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pays $entity)
    {
        $form = $this->createForm(new PaysType(), $entity, array(
            'action' => $this->generateUrl('admin_pays_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pays entity.
     *
     * @Route("/new", name="admin_pays_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Pays:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository('TigreboiteFunkylabBundle:Language')->findAll();
        $entity = new Pays();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
            'languages' => $languages,
        );
    }

    /**
     * Displays a form to edit an existing Pays entity.
     *
     * @Route("/{id}/edit", name="admin_pays_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Pays:form.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Pays')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pays entity.');
        }

        $editForm = $this->createEditForm($entity);

        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $default_locale = $this->container->getParameter('locale');
        $entity->setTranslatableLocale($default_locale);
        $em->refresh($entity);
        $translations = $repository->findTranslations($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
            'translations' => $translations,
            'languages' => $em->getRepository('TigreboiteFunkylabBundle:Language')->findAll(),
        );
    }

    /**
     * Creates a form to edit a Pays entity.
     *
     * @param Pays $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Pays $entity)
    {
        $form = $this->createForm(new PaysType(), $entity, array(
            'action' => $this->generateUrl('admin_pays_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Pays entity.
     *
     * @Route("/{id}", name="admin_pays_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Pays:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository('TigreboiteFunkylabBundle:Language')->findAll();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:Pays')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pays entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->updateLanguageEntity($entity);

            return $this->redirect($this->generateUrl('admin_pays_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
            'languages' => $languages,
        );
    }
    /**
     * Deletes a entity.
     *
     * @Route("/{id}", name="admin_pays_delete", options={"expose"=true})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:Pays')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sport entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }
}
