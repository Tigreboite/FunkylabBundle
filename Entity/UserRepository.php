<?php

namespace Tigreboite\FunkylabBundle\Entity;

class UserRepository extends BaseRepository
{
    public function findUserActivity($limit = 10)
    {
        return $this->createQueryBuilder('u')
          ->select('u')
          ->where('u.image is not null')
          ->innerJoin('u.activity', 'a')
          ->addGroupBy('u.id')
          ->setMaxResults($limit)
          ->orderBy('u.createdAt', 'DESC')
          ->getQuery()
          ->getResult();
    }

    /**
     * @param $query
     * @param $orderby
     * @param $order
     * @param $limit
     * @param $offset
     *
     * @return mixed
     */
    public function findDataQuery($query, $orderby, $order, $limit, $offset)
    {
        $qb = $this->createQueryBuilder('i');

        $qb->orderBy('i.'.$orderby, $order)
          ->setFirstResult($limit * $offset)
          ->setMaxResults($limit)
        ;

        if ($query) {
            $qb->where('i.firstname like :query')
              ->orWhere('i.lastname like :query')
              ->setParameter('query', '%'.$query.'%')
            ;
            $qb->andWhere('i.isarchived = 0 OR i.isarchived IS NULL');
        } else {
            $qb->where('i.isarchived = 0 OR i.isarchived IS NULL');
        }

        return $qb->getQuery()->getResult();
    }

    public function generateUniqueRefCust()
    {
        while (1) {
            $uniq_id = mt_rand(100000000, 999999999);

            $check = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.ref_cust = :ref')
            ->setParameter('ref', $uniq_id)
            ->getQuery()
            ->getResult();

            if (count($check) == 0) {
                break;
            }
        }

        return $uniq_id;
    }
}
