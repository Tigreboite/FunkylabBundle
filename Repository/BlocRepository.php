<?php

namespace Tigreboite\FunkylabBundle\Repository;

/**
 * BlocRepository.
 */
class BlocRepository extends BaseRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('position' => 'ASC'));
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
        } elseif ($type == 'page') {
            $qb->andWhere('b.page = :entityParent');
        }

        $qb->setParameter('entityParent', $entityParent);
        $qb->orderBy('b.position', 'ASC');

        return $qb->getQuery()->execute();
    }
}
