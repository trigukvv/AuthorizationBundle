<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthPermission;

class FillAuthPermissionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $permissions=[
            'read'=>'Чтение записи',
            'list'=>'Просмотр списка всех записей',
            //'listGroup'=>'Просмотр списка записей для группы',
            'create'=>'Создание записи',
            'update'=>'Изменение записи',
            'delete'=>'Удаление записи',
        ];
        
        foreach ($permissions as $name=>$description)
        {
            $permission = new AuthPermission();
            $permission->setName($name);
            $permission->setDescription($description);
            $manager->persist($permission);
        }   
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }      
}
