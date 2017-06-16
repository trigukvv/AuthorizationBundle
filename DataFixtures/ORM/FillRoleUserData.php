<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Repository\AuthRoleRepository;
use triguk\AuthorizationBundle\Repository\AuthScopeRepository;
use triguk\AuthorizationBundle\Repository\AuthPermissionRepository;
use triguk\AuthorizationBundle\Repository\AuthGrantRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FillRoleUserData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    
    

    
    
    
    
    //public function __construct(AuthRoleRepository $roleRepository)

    public function load(ObjectManager $manager)
    {
        
        
        
    }
    
    
    
    public function getOrder()
    {
        return 1000;
    }    
}
