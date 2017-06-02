<?php

namespace Tigreboite\FunkylabBundle\Manager;

class ActualityManager extends BaseManager
{
    public function getLatestActuality($categories = array())
    {
        return $this->getRepository()->findLatestActuality($categories);
    }

    public function preparePagination($category = null)
    {
        return $this->getRepository()->findAllPaginate($category);
    }

    public function getFromTags($tags, $currentId = null, $limit = null)
    {
        return $this->getRepository()->findFromTags($tags, $currentId, $limit);
    }
}
