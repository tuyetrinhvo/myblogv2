<?php
/**
 * Class Doc Comment
 *
 * PHP version 7.0
 *
 * @category PHP_Class
 * @package  AppBundle
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AppBundle\Entity\Avatar;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 *
 * @category PHP_Class
 * @package  AppBundle\DataFixtures\ORM
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
class LoadUserData extends AbstractFixture implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    /**
     * Private variable container
     *
     * @var ContainerInterface
     */
    private $_container;

    /**
     * Function setContainer
     *
     * @param ContainerInterface|null $container Some argument description
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->_container = $container;
    }

    /**
     * Function load
     *
     * @param ObjectManager $manager Some argument description
     *
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $avatar = new Avatar();
        $avatar->setExtension('profil-01.png');
        $avatar->setAlt('profil-01');

        $avatar1 = new Avatar();
        $avatar1->setExtension('profil-02.png');
        $avatar1->setAlt('profil-02');

        $password = $this->_container->get('security.password_encoder');

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@gmail.com');
        $user->setPassword($password->encodePassword($user, 'aqw'));
        $user->setRoles(['ROLE_USER']);
        $user->setAvatar($avatar);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($password->encodePassword($admin, 'zsx'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAvatar($avatar1);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();

        $this->addReference('myuser', $user);
        $this->addReference('myadmin', $admin);
    }

    /**
     * Function getOrder
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
