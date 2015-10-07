<?php

namespace Tigreboite\FunkylabBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tigreboite\FunkylabBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $users = array(
            array(
                'firstName' => 'Super',
                'lastName'  => 'Admin',
                'email'     => 'admin@admin.com',
                'username'  => 'admin@admin.com',
                'password'  => 'admin',
                'uniq'      => 11111111,
                'roles'     => array(
                    'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN'
                ),
            ),
        );

        $lang_en = $manager->getRepository("TigreboiteFunkylabBundle:Language", "default")->findOneByCode('en');

        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($users as $k => $user) {
            $entity = new User();
            
            $entity->setEmail($user['email']);
            $entity->setUsername($user['username']);
            $entity->setPlainPassword($user['password']);
            $entity->setEnabled(true);
            $entity->setLanguage($lang_en);

            $entity->setFirstName($user['firstName']);
            $entity->setLastName($user['lastName']);

            $entity->setNewsletter(false);
            $entity->setNewsletterPartner(false);
            $entity->setIsarchived(false);

            foreach ($user['roles'] as $role) {
                $entity->addRole($role);
            }

            $userManager->updateUser($entity);

            if ($k == 0) {
                $this->addReference('admin-user', $entity);
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
