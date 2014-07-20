<?php

namespace Tigreboite\FunkylabBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Tigreboite\FunkylabBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadPlayerData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $arrayAdmin = array(
            array(
                'firstName' => 'Super',
                'lastName'  => 'Admin',
                'email'     => 'admin@admin.com',
                'password'  => 'admin',
                'roles'     => array(
                    'ROLE_ADMIN',
                )
            )
        );

        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($arrayAdmin as $k=>$admin){
            $entity = new User();
            $entity->setEmail($admin['email']);
            $entity->setPlainPassword($admin['password']);
            $entity->setEnabled(true);
            $entity->setFirstName($admin['firstName']);
            $entity->setLastName($admin['lastName']);
            foreach ($admin['roles'] as $role){
                $entity->addRole($role);
            }

            $userManager->updateUser($entity);

            if($k==0)
                $this->addReference('admin-user', $entity);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
