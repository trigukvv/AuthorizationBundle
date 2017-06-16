<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthRole;

class FillAuthRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $roles=[
            'admin'=>'Администратор системы',
//            'moderator'=>'Модератор',
            'user'=>'Пользователь',
            'guest'=>'Гость',
        ];
        
        foreach ($roles as $name=>$description)
        {
            $role = new AuthRole();
            $role->setName($name);
            $role->setDescription($description);
            $manager->persist($role);
        }   
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }      
}
