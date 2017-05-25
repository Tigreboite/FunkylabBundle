<?php

namespace Tigreboite\FunkylabBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TagcoBundle\Service\FunkylabService;

class FunkylabCollector extends DataCollector implements DataCollectorInterface
{
    /**
     * @var FunkylabService
     */
    protected $service;

    /**
     * Constructor.
     *
     * @param FunkylabService $service
     */
    public function __construct(FunkylabService $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->service->getData();
    }

    /**
     * Returns profiled data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'funkylab.collector';
    }
}
