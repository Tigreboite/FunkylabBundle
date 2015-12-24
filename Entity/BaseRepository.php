<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

use Gedmo\Translatable\TranslatableListener;

class BaseRepository extends EntityRepository
{

    /**
     * @var string Default locale
     */
    protected $defaultLocale;

    /**
     * Sets default locale
     *
     * @param string $locale
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }


    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return Query
     */
    protected function getTranslatedQuery(QueryBuilder $qb, $locale = null)
    {
        $locale = null === $locale ? $this->defaultLocale : $locale;

        $query = $qb->getQuery();

        // TODO : Used ??
        // $query->setHint(
        //   Query::HINT_CUSTOM_OUTPUT_WALKER,
        //   'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        // );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query;
    }


    public function findDataTable($columns, $start, $length, $search_string, $order_column, $order_dir, $where_spe = array(),$locale="en")
    {
        $columuns_select = "";
        $first = true;
        $join = array();

        $qb = $this->createQueryBuilder('d');

        // Select columns
        foreach ($columns as &$col) {
            if(!empty($col['name'])) {
                if(!$first) $columuns_select .= ',';

                // Check join()
                if(strpos($col['name'], '.') !== false) {
                    $cut = explode('.', $col['name']);
                    $table = $cut[0];
                    $field = $cut[1];

                    if(!in_array($table,$join))
                    {
                        $join[]=$table;
                        $qb->leftJoin('d.'.$table, $table);
                    }
                    $qb->addSelect($table.'.'.$field.' as data_'.$col['data']);

                    $col['name'] = $table.'.'.$field;
                }
                elseif(!empty($col['spe']) && $col['spe'] == true) {

                    // SPE Count OneToMany
                    if(isset($col['count_one_to_many']) && $col['count_one_to_many'])
                    {
                        $qb->addSelect('COUNT('.ucfirst($col['name']).') as data_'.$col['data'])
                          ->leftJoin('d.'.$col['name'], ucfirst($col['name']))
                          ->addGroupBy('d.id');

                        $col['name'] = 'data_'.$col['data'];
                        $col['nosearch'] = true;
                    }

                    // SPE OneToMany GROUP CONCAT
                    if(isset($col['group_concat_one_to_many']) && $col['group_concat_one_to_many'])
                    {
                        $field = ucfirst($col['table']).'.'.$col['field'];
                        $qb->addSelect('GROUP_CONCAT('.$field.') as data_'.$col['data'])
                          ->leftJoin('d.'.$col['table'], ucfirst($col['table']))
                          ->addGroupBy('d.id');

                        $col['name'] = 'data_'.$col['data'];
                        $col['nosearch'] = true;
                    }

                }
                elseif($col['name'] == 'action') {
                    $qb->addSelect('d.id');
                    $col['name'] = 'd.id';
                }
                else {
                    $qb->addSelect('d.'.$col['name'].' as data_'.$col['data']);
                    $col['name'] = 'd.'.$col['name'];
                }
            }

            $first = false;
        }

        $countAllDatas = count($qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

        // Search filter
        if(!empty($search_string)) {
            $parameters = array();
            $index = 0;

            foreach ($columns as $col) {
                if(!empty($col['name']) && $col['name'] != 'action' && empty($col['nosearch'])) {
                    $qb->orWhere($col['name'].' LIKE ?'.$index);

                    $parameters[] = '%'.$search_string.'%';

                    $index++;
                }
            }

            $qb->setParameters($parameters);
        }

        // WHERE SPE
        if(!empty($where_spe)) {
            foreach ($where_spe as $where) {
                $qb->andWhere($where);
            }
        }

        $countFiltered = count($qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

        // Order
        if($columns[$order_column]['name'] != 'action') {
            $qb->orderBy($columns[$order_column]['name'], strtoupper($order_dir));
        }

        $query = $qb->setMaxResults($length)
          ->setFirstResult($start);

        $q = $this->getTranslatedQuery($query, $locale);
        $datas = $q->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $result = array();
        foreach ($datas as $data) {
            $index = 0;
            unset($data[0]);
            foreach ($data as $value) {
                $tmp[$index] = (empty($value) ? "" : $value);
                $index++;
            }
            $result[] = $tmp;
        }

        $result['count_all'] = $countAllDatas;
        $result['count_filtered'] = $countFiltered;

        return $result;
    }

    /**
     * @param $query
     * @param $orderby
     * @param $order
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function findDataQuery($query,$orderby,$order,$limit,$offset)
    {
        $qb = $this->dataQuery($query);

        $qb->orderBy('i.'.$orderby, $order)
          ->setFirstResult($offset*$limit)
          ->setMaxResults($limit)
        ;

        return $qb->getQuery()->getResult();
    }

    public function dataQuery($query)
    {
        $qb = $this->createQueryBuilder('i');

        if($query)
        {
            $qb->andWhere('i.title like :query OR i.summary like :query')
              ->setParameter('query', '%'.$query.'%')
            ;
        }

        return $qb;
    }

    public function countDataQuery($query)
    {
        $qb = $this->dataQuery($query);
        $query = $qb->select('COUNT(i.id)')->getQuery();
        return $query->getSingleScalarResult();
    }
}
