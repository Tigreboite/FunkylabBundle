<?php

namespace Tigreboite\FunkylabBundle\Manager;

use Doctrine\ORM\EntityManager;
use Tigreboite\FunkylabBundle\Factory\BlocFactory;

class BlocManager extends BaseManager
{
    private $blocFactory;

    public function __construct(EntityManager $em, $class, BlocFactory $blocFactory)
    {
        parent::__construct($em, $class);
        $this->blocFactory = $blocFactory;
    }

    public function findAllByParent($type, $id)
    {
        $blocParent = $this->blocFactory->getBlocParent($type, $id);

        $repo = $this->getRepository();
        $res = $repo->findAllByParent($type, $blocParent);

        return $res;
    }
}
