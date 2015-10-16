<?php
/**
 * Code by Cyril Pereira, Julien Hay
 * Extreme-Sensio 2015
 */
namespace Tigreboite\FunkylabBundle\Generator\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tigreboite\FunkylabBundle\Entity\Simpleform;
use Tigreboite\FunkylabBundle\Form\SimpleformType;


/**
 * Simpleform controller.
 *
 * @Route("/admin/simpleform")
 */
class SimpleformController extends Controller
{

    protected $formType   = 'SimpleformType';
    protected $route_base = 'admin_simpleform';
    protected $repository = '%bundle_name%:%entity_name%';

    /**
     * Lists all Simpleform entities.
     *
     * @Route("/", name="admin_simpleform")
     * @Method("GET")
     * @Template()
     * @Menu("Simpleform", dataType="string",icon="fa-flag",groupe="CMS")
     * %security_roles%
     */
    public function indexAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $repo   = $em->getRepository($this->repository);
        $entity = $repo->findOneBy(array(), array('id' => 'DESC'));

        if (!$entity) {
            $entity   = new Car();
            $editForm = $this->createCreateForm($entity);
        } else {
            $editForm = $this->createEditForm($entity);
        }

        return array('entity' => $entity, 'form' => $editForm->createView(), 'ajax' => $request->isXmlHttpRequest());

    }
    /**
     * Creates a new Simpleform entity.
     *
     * @Route("/", name="admin_simpleform_create")
     * @Method("POST")
     * @Template("TigreboiteFunkylabBundle:Simpleform:index.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Simpleform();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl($this->route_base));
        }

        return array(
          'entity' => $entity,
          'form'   => $form->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Creates a form to create a Simpleform entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Simpleform $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new SimpleformType($em), $entity, array(
          'action' => $this->generateUrl($this->route_base.'_create'),
          'method' => 'POST',
          'allow_extra_fields'=>true,
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to edit a Simpleform entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Simpleform $entity)
    {
        $em = $this->get('doctrine')->getManager();
        $form = $this->createForm(new SimpleformType($em), $entity, array(
          'action' => $this->generateUrl('admin_simpleform_update', array('id' => $entity->getId())),
          'method' => 'PUT',

        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }


    /**
     * Edits an existing Simpleform entity.
     *
     * @Route("/{id}", name="admin_simpleform_update")
     * @Method("PUT")
     * @Template("TigreboiteFunkylabBundle:Simpleform:form.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl($this->route_base.'_edit', array('id' => $id)));
        }

        return array(
          'entity'      => $entity,
          'form'   => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest()
        );
    }

    /**
     * Upload files
     *
     * @Route("/upload", name="admin_simpleform_upload")
     */
    public function uploadAction(Request $request)
    {
        $dir_path = 'medias/%entity_path_file%/';
        $data = array('success'=>false);
        $uploadedFile = $request->files->get('file');

        if ($uploadedFile)
        {
            $file = $uploadedFile->move('../web/'.$dir_path, $uploadedFile->getClientOriginalName());
            if($file)
            {
                $data = array(
                  'success'=>true,
                  'filename'=>$uploadedFile->getClientOriginalName(),
                  'path'=>$dir_path.$uploadedFile->getClientOriginalName()
                );
            }
        }

        return new JsonResponse($data);
    }

}
