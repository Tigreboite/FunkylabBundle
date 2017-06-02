<?php

namespace Tigreboite\FunkylabBundle\Repository;

/**
 * BlocRepository.
 */
class BlocRepository extends BaseRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('ordre' => 'ASC'));
    }

    public function findAllByParent($type, $entityParent)
    {
        $qb = $this->createQueryBuilder('b');

        $qb->select('b')
            ->where('b.type = :type')
            ->setParameter('type', $type)
        ;

        if ($type == 'actuality') {
            $qb->andWhere('b.actuality = :entityParent');
        } elseif ($type == 'advice') {
            $qb->andWhere('b.advice = :entityParent');
        } elseif ($type == 'rh') {
            $qb->andWhere('b.rh = :entityParent');
        } elseif ($type == 'page') {
            $qb->andWhere('b.page = :entityParent');
        }

        $qb->setParameter('entityParent', $entityParent);
        $qb->orderBy('b.ordre', 'ASC');

        return $qb->getQuery()->execute();
    }
}
