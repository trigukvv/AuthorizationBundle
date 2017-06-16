<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthScope;

class FillAuthScopeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $scopes=[
            'owner'=>'Пользователь является владельцем объекта',
            'group'=>'Пользователь входит в ту же группу, что и владелец объекта',
            'all'=>'Пользователь имеет доступ ко всем записям',
            //'anonymous'=>'Анонимный пользователь',
        ];
        
        foreach ($scopes as $name=>$description)
        {
            $scope = new AuthScope();
            $scope->setName($name);
            $scope->setDescription($description);
            $manager->persist($scope);
        }   
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }          
}
