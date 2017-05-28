<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DatagridController extends BaseController
{
    protected $entityName = 'Datagrid';
    protected $formType = 'DatagridType';
    protected $route_base = 'admin_datagrid';
    protected $repository = 'Tigreboite:Datagrid';
    protected $dir_path = 'medias/datagrid/';

    /**
     * Get a list to feed datagrid.
     *
     * @param Request $request
     *
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
        $entities = $em->getRepository($this->repository)
          ->findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir);

        $serializer = $this->get('jms_serializer');

        // Construct JSON
        $data_to_return = array();
        $data_to_return['draw'] = $draw;
        $data_to_return['recordsTotal'] = $entities['count_all'];
        $data_to_return['recordsFiltered'] = $entities['count_filtered'];

        unset($entities['count_all']);
        unset($entities['count_filtered']);
        $data_to_return['data'] = $this->dataTransformer($entities);

        return new Response($serializer->serialize($data_to_return, 'json'));
    }

    protected function dataTransformer($entities)
    {
        foreach ($entities as &$data) {
            foreach ($data as &$column) {
                if ($column instanceof \DateTime) {
                    /**
                     * @var \DateTime
                     */
                    $column = $column->format('d/m/Y H:i');
                }
            }
        }

        return $entities;
    }
}
