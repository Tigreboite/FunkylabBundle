<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tigreboite\FunkylabBundle\Event\EntityEvent;
use Tigreboite\FunkylabBundle\TigreboiteFunkylabEvent;

class BaseController extends Controller
{
    protected $entityName = 'Base';
    protected $formType = 'BaseType';
    protected $route_base = 'admin_Base';
    protected $repository = 'Tigreboite:Base';
    protected $dir_path = 'medias/Base/';

    public function indexAction()
    {
        return array();
    }

    public function createAction(Request $request)
    {
        $entity = new $this->entityName();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(in_array( "Tigreboite\\FunkylabBundle\\Traits\\Blameable", class_uses($entity) )){
                $entity->setCreatedBy($this->getUser());
            }

            $em->persist($entity);
            $em->flush();

            $event = new EntityEvent($entity);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_CREATED, $event);

            return $this->redirect($this->generateUrl($this->route_base));
        }

        return array(
            'error' => self::getErrorMessages($form),
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    protected function createCreateForm($entity)
    {
        $form = $this->createForm($this->formType, $entity, array(
            'action' => $this->generateUrl($this->route_base.'_create'),
            'method' => 'POST',
            'allow_extra_fields' => true,
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    protected static function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $err = self::getErrorMessages($child);
                if (!empty($err)) {
                    $errors[$child->getName()] = $err;
                }
            }
        }
        $checkError = current($errors);
        if (!empty($checkError)) {
            return $errors;
        } else {
            return;
        }
    }

    public function newAction(Request $request)
    {
        $entity = new $this->entityName();
        $form = $this->createCreateForm($entity);

        return array(
            'error' => self::getErrorMessages($form),
            'entity' => $entity,
            'form' => $form->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Base entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    protected function createEditForm($entity)
    {
        $form = $this->createForm($this->formType, $entity, array(
            'action' => $this->generateUrl($this->route_base.'_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $form;
    }

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

            if(in_array( "Tigreboite\\FunkylabBundle\\Traits\\Blameable", class_uses($entity) )){
                $entity->setUpdatedBy($this->getUser());
                $em->persist($entity);
            }

            $em->flush();

            $event = new EntityEvent($entity);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_UPDATED, $event);

            return $this->redirect($this->generateUrl($this->route_base.'_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'ajax' => $request->isXmlHttpRequest(),
        );
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($this->repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $event = new EntityEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(TigreboiteFunkylabEvent::ENTITY_DELETED, $event);

        $em->remove($entity);
        $em->flush();

        return new Response('Deleted');
    }

    public function uploadAction(Request $request)
    {
        $data = array('success' => false);
        $uploadedFile = $request->files->get('file');

        if ($uploadedFile) {
            $file = $uploadedFile->move('../web/'.$this->dir_path, $uploadedFile->getClientOriginalName());
            if ($file) {
                $data = array(
                    'success' => true,
                    'filename' => $uploadedFile->getClientOriginalName(),
                    'path' => $this->dir_path.$uploadedFile->getClientOriginalName(),
                );
            }
        }

        return new JsonResponse($data);
    }

    public function ajaxAction(Request $request)
    {
        $query = $request->get('q', '');
        $orderby = $request->get('orderyby', $this->orderBy);
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
