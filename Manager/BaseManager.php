<?php

namespace Tigreboite\FunkylabBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository\RepositoryFactory;

class BaseManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var RepositoryFactory
     */
    protected $repository;

    protected $className;

    /**
     * BaseManager constructor.
     *
     * @param EntityManager $entityManager
     * @param $className
     */
    public function __construct(EntityManager $entityManager, $className)
    {
        $this->entityManager = $entityManager;
        $this->className = $className;
        $this->repository = $entityManager->getRepository($this->className);
    }

    /**
     * Return all entity of the class.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Returns all entities for given criteria.
     *
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Returns all entities for given criteria.
     *
     * @param array $criteria
     *
     * @return array
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Persist the entity.
     *
     * @param $entity
     * @param bool $doFlush
     *
     * @return mixed
     */
    public function save($entity, $doFlush = true)
    {
        $this->entityManager->persist($entity);
        if ($doFlush) {
            $this->entityManager->flush();
        }

        return $entity;
    }

    /**
     * Delete the given entity.
     *
     * @param object $entity An entity instance
     */
    public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * Return one entity by id.
     *
     * @param $id
     *
     * @return null|object
     */
    public function findOneById($id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Flush persisted entities.
     */
    public function flush()
    {
        $this->entityManager->flush();
    }
    /**
     * Refresh persisted entities.
     */
    public function refresh($entity)
    {
        $this->entityManager->refresh($entity);
    }
    /**
     * Clears the repository, causing all managed entities to become detached.
     */
    public function clear()
    {
        $this->entityManager->clear();
    }
}
