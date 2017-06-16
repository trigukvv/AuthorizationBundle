<?php
namespace triguk\AuthorizationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Entity\AuthPermission;
use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Repository\AuthRoleRepository;
use triguk\AuthorizationBundle\Repository\AuthScopeRepository;
use triguk\AuthorizationBundle\Repository\AuthPermissionRepository;
use triguk\AuthorizationBundle\Repository\AuthGrantRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FillAuthGrantData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    private $bundleName,$userEntity;
    
    protected $roleRepository, $permissionRepository, $scopeRepository;
    

    
    
    
    
    //public function __construct(AuthRoleRepository $roleRepository)

    public function load(ObjectManager $manager)
    {


        $this->roleRepository=$manager->getRepository(AuthRole::class);
        $this->permissionRepository=$manager->getRepository(AuthPermission::class);
        $this->scopeRepository=$manager->getRepository(AuthScope::class);
        $this->grantRepository=$manager->getRepository(AuthGrant::class);
        $this->objectRepository=$manager->getRepository(AuthObject::class);

                
        $adminRole=$this->roleRepository->findOneByName('admin');
        $userRole=$this->roleRepository->findOneByName('user');
        
        $allPermissions=$this->permissionRepository->findAll();
        
        
        /*
        $listPermission=$this->permissionRepository->findOneByName('list');
        $readPermission=$this->permissionRepository->findOneByName('read');
        $createPermission=$this->permissionRepository->findOneByName('create');
        $updatePermission=$this->permissionRepository->findOneByName('update');
        $deletePermission=$this->permissionRepository->findOneByName('delete');
        
        
        
        $allPermissions=[$listPermission,
                         $readPermission,
                         $createPermission,
                         $updatePermission,
                         $deletePermission
                        ];
        
        
        
        $ownerScope=$this->scopeRepository->findOneByName('owner');
        $groupScope=$this->scopeRepository->findOneByName('group');
        $allScope=$this->scopeRepository->findOneByName('all');
*/
        
        $allObjects=$this->objectRepository->findAll();
        
        $scopeAll=$this->scopeRepository->findOneByName('all');
        
/*
        $allScopes=[$ownerScope,
                    $groupScope,
                    $allScope
                   ];
*/
        $adminGrants=[];

        foreach ($allObjects as $object)
        {
            foreach ($allPermissions as $permission)
            {
                //foreach ($allScopes as $scope)
                {
                    $adminGrants[]=['object'=>$object,'scope'=>$scopeAll,'permission'=>$permission];
                }
            }
        }

        $this->grantRepository->syncRoleGrants($adminRole, $adminGrants);


        
    }
    
    
    
    public function getOrder()
    {
        return 1000;
    }    
}
