<?php

namespace Tigreboite\FunkylabBundle\Entity;

class BlogCommentRepository extends BaseRepository
{
    public function findCommentsLimit($id, $first = 0, $max = 100, $id_parent = 0)
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.blog', 'p')
            ->setFirstResult($first * $max)
            ->setMaxResults($max)
            ->orderBy('i.id', 'DESC')
            ->andWhere('i.id_parent = :idparent')
            ->andWhere('i.isarchived = 0 or i.isarchived IS NULL')
            ->setParameter('idparent', $id_parent);

        $qb->leftJoin('i.user', 'u');
        $qb->andWhere('u.isarchived = 0 OR u.isarchived IS NULL');

        if ($id != 0) {
            $qb = $qb->andWhere('p.id = :id')
                ->setParameter('id', $id);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findDataTableBlogComment($columns, $start, $length, $search_string, $order_column, $order_dir, $blog_id)
    {
        foreach ($columns as &$col) {
            if (!empty($col['name']) && $col['name'] == 'usefull') {
                $col['spe'] = true;
                $col['group_concat_one_to_many'] = true;
                $col['table'] = 'commentusefull';
                $col['field'] = 'isusefull';
            }
        }

        $where_spe = array();
        $where_spe[] = 'd.blog = '.$blog_id;

        return parent::findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir, $where_spe);
    }
}
