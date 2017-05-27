<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tigreboite\FunkylabBundle\Entity\Language;
use Tigreboite\FunkylabBundle\Form\Type\LanguageType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Language controller.
 *
 * @Route("/language")
 */
class LanguageController extends Controller
{
    /**
     * Lists all Language entities.
     *
     * @Route("/", name="admin_language")
     * @Method("GET")
     * @Template()
     * @Menu("Languages", dataType="string",icon="fa-flag",groupe="CMS")
     * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Lists all Language entities.
     *
     * @param Request $request
     * @Route("/list", name="admin_language_list", options={"expose"=true})
     * @Method("GET")
     * @return Response
     */
    public function listAction(Request $request)
    {
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
        $entities = $em->getRepository('TigreboiteFunkylabBundle:Language')
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
     * Creates a new Language entity.
     * @param Request $request
     * @Route("/", name="admin_language_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Language:form.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Language();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_language'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to create a Language entity.
     *
     * @param Language $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Language $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(LanguageType::class, $entity, array(
            'action' => $this->generateUrl('admin_language_create'),
            'method' => 'POST',
            'allow_extra_fields' => true,
            'entityManager'=>$em
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Language entity.
     *
     * @Route("/new", name="admin_language_new")
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Language:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Language();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/{id}/reset", name="admin_language_reset", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Language:reset.html.twig")
     * @return array
     */
    public function resetAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $em = $this->get('doctrine')->getManager();
        $user = $em->getRepository('TigreboiteFunkylabBundle:User')->findByLanguage($entity);
        foreach ($user as $u) {
            $u->setCgu(false);
            $em->persist($u);
        }
        $em->flush();

        return array(
            'user' => $user,
            'entity' => $entity,
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Displays a form to edit an existing Language entity.
     * @param Request $request
     * @param $id
     * @Route("/{id}/edit", name="admin_language_edit", options={"expose"=true})
     * @Method("GET")
     * @Template("TigreboiteFunkylabBundle:Language:form.html.twig")
     * @return array
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    /**
     * Creates a form to edit a Language entity.
     *
     * @param Language $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Language $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(LanguageType::class, $entity, array(
            'action' => $this->generateUrl('admin_language_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'entityManager'=>$em
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Language entity.
     * @param Request $request
     * @param $id
     * @Route("/{id}", name="admin_language_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Language:form.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TigreboiteFunkylabBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_language_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }
    /**
     * Deletes a Language entity.
     * @param Request $request
     * @param $id
     * @Route("/{id}", name="admin_language_delete", options={"expose"=true})
     * @Method("DELETE")
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TigreboiteFunkylabBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }
    /**
     * Upload files.
     * @param Request $request
     * @Route("/upload/pdfpayment", name="admin_language_upload", options={"expose"=true})
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $dir_path = 'medias/pdf_payment/';
        $data = array('success' => false);
        $uploadedFile = $request->files->get('images');

        if ($uploadedFile) {
            $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
            if ($file) {
                $data = array(
                  'success' => true,
                  'filename' => $uploadedFile->getClientOriginalName(),
                  'path' => $dir_path.$uploadedFile->getClientOriginalName(),
                );
            }
        }

        return new JsonResponse($data);
    }
    /**
     * Upload files.
     * @param Request $request
     * @Route("/upload/image", name="admin_language_image_upload", options={"expose"=true})
     * @return JsonResponse
     */
    public function uploadimageAction(Request $request)
    {
        $dir_path = 'medias/language/';
        $data = array('success' => false);
        $uploadedFile = $request->files->get('images');

        if ($uploadedFile) {
            $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
            if ($file) {
                $data = array(
                  'success' => true,
                  'filename' => $uploadedFile->getClientOriginalName(),
                  'path' => $dir_path.$uploadedFile->getClientOriginalName(),
                );
            }
        }

        return new JsonResponse($data);
    }
}
