<?php

namespace Tigreboite\FunkylabBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Tigreboite\FunkylabBundle\Entity\BaseRepository;

/**
 * ActualityRepository.
 */
class ActualityRepository extends BaseRepository
{
    public function findAllPaginate($category)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a');

        $qb = $this->whereIsPublished($qb);

        if ($category !== null) {
            $qb->andWhere('a.category = :category')
                ->setParameter('category', $category);
        }
        $qb->orderBy('a.dateStart', 'DESC');

        return $qb;
    }

    public function findFromTags($tags, $currentId = null, $limit = null)
    {
        if (!is_array($tags)) {
            $tags = explode(',', trim($tags));
        }

        if (!count($tags)) {
            return array();
        }

        $qb = $this->createQueryBuilder('a')
            ->select('a');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        $qb = $this->whereIsPublished($qb);

        if (count($tags) > 0) {
            $whereTags = array();
            $whereParam = array();
            foreach ($tags as $k => $tag) {
                $tag = trim($tag);
                $whereTags[] = 'a.tags like :param'.$k;
                $whereParam['param'.$k] = '%'.$tag.'%';
            }
            $qb->andWhere(implode(' or ', $whereTags));
            foreach ($whereParam as $k => $val) {
                $qb->setParameter($k, $val);
            }
        }

        if ($currentId !== null) {
            $qb->andWhere('a.id != :currentId')
                ->setParameter('currentId', $currentId);
        }

        $qb->orderBy('a.id', 'DESC');

        return $qb->getQuery()->execute();
    }

    public function findLatestActuality($categories = array())
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a');
        $qb = $this->whereIsPublished($qb);

        if (count($categories) > 0) {
            $qb->andWhere('a.category IN(:categories)')
                ->setParameter('categories', $categories);
        }
        $qb->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function whereIsPublished(QueryBuilder $queryBuilder)
    {
        $now = new \DateTime();

        $queryBuilder->andWhere('a.published = true')
            ->andWhere('a.dateStart <= :now OR a.dateStart is NULL')
            ->andWhere('a.dateEnd > :now OR a.dateEnd is NULL')
            ->setParameter('now', $now->format('Y-m-d'))
        ;

        return $queryBuilder;
    }
}
