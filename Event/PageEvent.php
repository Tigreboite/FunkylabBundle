<?php

namespace Tigreboite\FunkylabBundle\Event;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Tigreboite\FunkylabBundle\Entity\Page;

class PageEvent extends BaseEvent
{
    private $page;

    public function __construct(Container $container, Page $page)
    {
        parent::__construct($container);
        $this->page = $page;
    }

    public function getPage() : Page
    {
        return $this->Page;
    }
}
