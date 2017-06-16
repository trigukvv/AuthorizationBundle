<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthObject;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FillAuthObjectData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    private $bundleName,$userEntity;
    
    public function load(ObjectManager $manager)
    {
        $this->bundleName=$this->container->getParameter('bundleShortName');
        $this->userEntity=$this->container->getParameter('userEntity');
        $objects=[
            [
                'title'=>"{$this->bundleName}:AuthRole",
                'description'=>'Роли',
                'titleSingular'=>'Роль',
                'titlePlural'=>'Роли',
                'route'=>'admin_authrole_index'
            ],            [
                'title'=>"{$this->bundleName}:AuthObject",
                'description'=>'Объекты веб-приложения',
                'titleSingular'=>'Объект веб-приложения',
                'titlePlural'=>'Объекты веб-приложения',
                'route'=>'admin_authobject_index'
            ],
            [
                'title'=>"{$this->bundleName}:AuthScope",
                'description'=>'Масштабы доступа',
                'titleSingular'=>'Масштаб доступа',
                'titlePlural'=>'Масштабы доступа',
                'route'=>'admin_authscope_index'
            ],
            [
                'title'=>"{$this->bundleName}:AuthPermission",
                'description'=>'Разрешения',
                'titleSingular'=>'Разрешение',
                'titlePlural'=>'Разрешения',
                'route'=>'admin_authpermission_index'
            ],
            [
                'title'=>"{$this->bundleName}:AuthGrant",
                'description'=>'Выданные разрешения',
                'titleSingular'=>'Выданное разрешение',
                'titlePlural'=>'Выданные разрешения',
                'route'=>'admin_authgrant_index'
            ]
        ];
        
        foreach ($objects as $object)
        {
            $permission = new AuthObject();
            $permission->setName($object['title']);
            $permission->setDescription($object['description']);
            $permission->setTitleSingular($object['titleSingular']);
            $permission->setTitlePlural($object['titlePlural']);
            $permission->setRoute($object['route']);
            $manager->persist($permission);
        }   
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }      
}
