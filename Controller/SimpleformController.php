<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class SimpleformController extends BaseController
{
    protected $entityName = 'Simpleform';
    protected $formType = 'SimpleformType';
    protected $route_base = 'admin_simpleform';
    protected $repository = 'Tigreboite:Simpleform';
    protected $dir_path = 'medias/simpleform/';

    /**
     * Display a simpleform.
     *
     * @return array
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository($this->repository);
        $entity = $repo->findOneBy(array(), array('id' => 'DESC'));

        if (!$entity) {
            $entity = new $this->entityName();
            $editForm = $this->createCreateForm($entity);
        } else {
            $editForm = $this->createEditForm($entity);
        }

        return array('entity' => $entity, 'form' => $editForm->createView(), 'ajax' => $request->isXmlHttpRequest());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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

            return $this->redirect($this->generateUrl($this->route_base));
        }

        return array(
          'entity' => $entity,
          'form' => $editForm->createView(),
          'ajax' => $request->isXmlHttpRequest(),
        );
    }
}
