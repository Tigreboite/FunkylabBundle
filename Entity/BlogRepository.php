<?php

namespace Tigreboite\FunkylabBundle\Entity;

class BlogRepository extends BaseRepository
{
    /**
     * @param       $columns
     * @param       $start
     * @param       $length
     * @param       $search_string
     * @param       $order_column
     * @param       $order_dir
     * @param array $where_spe
     *
     * @return array
     */
    public function findDataTableBlog($columns, $start, $length, $search_string, $order_column, $order_dir, $where_spe = array())
    {
        foreach ($columns as &$col) {
            if (!empty($col['name']) && $col['name'] == 'nbcomment') {
                $col['spe'] = true;
                $col['count_one_to_many'] = true;
                $col['name'] = 'blogcomments';
            }
        }

        $where_spe = array();

        return parent::findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir, $where_spe);
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
            $qb->where('i.title like :query')
              ->orWhere('i.content like :query')
              ->setParameter('query', '%'.$query.'%')
            ;
        }

        return $qb->getQuery()->getResult();
    }

    public function getFilteredBlog($filter = false, $order = 'DESC', $limit = 3,  $offset = 0)
    {
        $qb = $this->createQueryBuilder('i');

        $qb->orderBy('i.id', $order)
          ->setFirstResult($limit * $offset)
          ->setMaxResults($limit)
          ->andWhere('i.parent is null')
          ->andWhere('i.status = 1');

        if ($filter) {
            $qb->andWhere('i.type = :filter')
              ->setParameter('filter', $filter);
        }

        return $qb->getQuery()->getResult();
    }

    public function findNextBlog($id)
    {
        $tmp = $this->createQueryBuilder('d')
            ->where('d.id > :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->orderBy('d.id', 'ASC');

        return $this->getOneResult($tmp->getQuery());
    }

    public function findPreviousBlog($id)
    {
        $tmp = $this->createQueryBuilder('d')
            ->where('d.id < :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->orderBy('d.id', 'DESC');

        return $this->getOneResult($tmp->getQuery());
    }

    protected function getOneResult(\Doctrine\ORM\Query $query)
    {
        $result = $query->getResult();

        return isset($result[0]) ? $result[0] : null;
    }

    public function getCountType(\Tigreboite\FunkylabBundle\Entity\BlogType $type = null)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('count(b.id)');
        if ($type) {
            $qb->where('b.type = :type')
                ->setParameter('type', $type);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPublished($sort, $order, $limit, $offset)
    {
        $qb = $this->createQueryBuilder('b');

        $qb->where('b.parent is null');
        $qb->andWhere('b.status = 1');

        $qb->orderBy('b.'.$sort, $order);
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }
}
