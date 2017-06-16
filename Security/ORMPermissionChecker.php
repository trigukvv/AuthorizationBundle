<?php

namespace triguk\AuthorizationBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManagerInterface;
use triguk\AuthorizationBundle\Helper\EntityNameExtractor;
use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Entity\AuthPermission;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Entity\HasOwner;
use triguk\AuthorizationBundle\Entity\HasGroups;
use triguk\AuthorizationBundle\Entity\HasAuthRoles;


class ORMPermissionChecker implements PermissionChecker
{

    protected $manager, $nameExtractor;
    protected $allGrants;
    protected $grantRepository,$scopeRepository,$permissionRepository,$roleRepository,$objectRepository;
    protected $scopeAll,$scopeGroup, $scopeOwner;
    protected $objectScopePermissionRoleTree;
    protected $usersRoleNames;
    protected $allScopes,$allPermissions;
    
    
    
    public function __construct(EntityManagerInterface $manager, EntityNameExtractor $nameExtractor)
    {
        $this->manager=$manager;
        $this->nameExtractor=$nameExtractor;
        
        $this->grantRepository=$manager->getRepository(AuthGrant::class);
        $this->scopeRepository=$manager->getRepository(AuthScope::class);        
        $this->permissionRepository=$manager->getRepository(AuthPermission::class);  
        $this->roleRepository=$manager->getRepository(AuthRole::class);  
        $this->objectRepository=$manager->getRepository(AuthObject::class);

        $this->allScopes=$this->scopeRepository->findAll();
        $this->allPermissions=$this->permissionRepository->findAll();        
        $this->allGrants=$this->grantRepository->getAllGrants();
        //$this->scopeAll=$this->scopeRepository->findOneByName('all');
        //$this->scopeGroup=$this->scopeRepository->findOneByName('group');
        //$this->scopeOwner=$this->scopeRepository->findOneByName('owner');
        $this->objectScopePermissionRoleTree=$this->getGrantTree();
        $this->usersRoleNames=[];

    }    
    public function getSupportedPermissionNames()
    {
        $names=[];
        foreach ($this->allPermissions as $permission)
            $names[]=$permission->getName();
            
        foreach ($this->allScopes as $permission)
            $names[]='list'.ucfirst($permission->getName());
        return $names;
    }
    protected function getGrantTree()
    {
        $grantTree=[];
        foreach ($this->allGrants as $grant)
        {
            $objectName=$grant->getAuthObject()->getName();
            if (!array_key_exists($objectName,$grantTree))
            {
                $grantTree[$objectName]=[];                
            }

            $scopeName=$grant->getAuthScope()->getName();
            if (!array_key_exists($scopeName,$grantTree[$objectName]))
            {
                $grantTree[$objectName][$scopeName]=[];                
            }
            $permissionName=$grant->getAuthPermission()->getName();
            if (!array_key_exists($permissionName,$grantTree[$objectName][$scopeName]))
            {
                $grantTree[$objectName][$scopeName][$permissionName]=[];                
            }
            $roleName=$grant->getAuthRole()->getName();

            $grantTree[$objectName][$scopeName][$permissionName][]=$roleName;
        }
        return $grantTree;
    }
    
    
    private function checkPermissionScopeAll0($permission, $object, $user, $roles)
    {
                
        foreach ($roles as $role)
        {
            
            if ($this->grantRepository->grantExists($role, $object, $this->scopeAll, $permission))
            {
                return true;
            }
        }
        return false;
    }
    private function convertToIds(&$groups)
    {
        foreach ($groups as &$group)
        {
            if (is_object($group) && method_exists($group,'getId'))
            {
                $group=$group->getId();
            }
        }
    }
    private function areSameGroups($user, $object)
    {
        $userGroups=is_array($user->getGroups())? $user->getGroups() : $user->getGroups()->toArray();
        $objectGroups=is_array($object->getGroups()) ? $object->getGroups() : $object->getGroups()->toArray();

        $sameGroup=false;
        $this->convertToIds($userGroups);
        $this->convertToIds($objectGroups);
        return count(array_intersect($userGroups,$objectGroups))>0;
    }
    
    
   
    
    
    private function getUserRoleNames($user)
    {
        
        $roleUser='user';
        $roleGuest='guest';
        $roleNames=[];
        if ($user instanceof HasAuthRoles)
        {
            if (array_key_exists($user->getId(),$this->usersRoleNames))
            {
                $roleNames=$this->usersRoleNames[$user->getId()];
            }
            else
            {
                $roles=$user->getAuthRoles();
                foreach ($roles as $role)
                {
                    $roleNames[]=$role->getName();
                }
                $roleNames[]=$roleUser;
                $this->usersRoleNames[$user->getId()]=$roleNames;
            }
        }
        else
        {
            $roleNames=[$roleGuest];
        }
        return $roleNames;
            
    }
    private function checkPermissionCustomScope($scopePermissionRoleTree, $permissionName,  $userRoleNames,$scopeName)
    {

        if (array_key_exists($scopeName,$scopePermissionRoleTree))
        {
            $permissionRoleTree=$scopePermissionRoleTree[$scopeName];
            
            if (array_key_exists($permissionName,$permissionRoleTree))
            {
                
                $allowedRoleNames=$permissionRoleTree[$permissionName];

                $matchCount=count(array_intersect($allowedRoleNames,$userRoleNames));

                return $matchCount>0;
            }
            
        }
        return false;
    }

    private function checkListPermission($scopePermissionRoleTree, $permissionName,  $userRoleNames)
    {

        if (substr($permissionName,0,4)!='list') return false;
        $scopeName=strtolower(str_replace('_','',substr($permissionName,4)));

        return $this->checkPermissionCustomScope($scopePermissionRoleTree, 'list',  $userRoleNames,$scopeName);
    }
    
    private function checkPermissionScopeAll($scopePermissionRoleTree, $permissionName,  $userRoleNames)
    {
        return $this->checkPermissionCustomScope($scopePermissionRoleTree, $permissionName,  $userRoleNames,'all');
    }    
    private function checkPermissionScopeGroup($user,$entity,$scopePermissionRoleTree, $permissionName,  $userRoleNames)
    {
        if (! $user instanceof HasAuthRoles) return false;
        if (is_string($entity)) return false;
        if (method_exists($entity,'getGroups') && method_exists($user,'getGroups'))
        {
            if ($this->areSameGroups($user, $entity))
            {

                return $this->checkPermissionCustomScope($scopePermissionRoleTree, $permissionName,  $userRoleNames,'group');
            }
        }
        return false;
    }
    private function checkPermissionScopeOwner($user,$entity,$scopePermissionRoleTree, $permissionName,  $userRoleNames)
    {
        if (! $user instanceof HasAuthRoles) return false;
        if (is_string($entity)) return false;
        if ($entity instanceof HasOwner)
        {
            
            $owner=$entity->getOwner();
            if ($owner->getId()==$user->getId())
            {

                return $this->checkPermissionCustomScope($scopePermissionRoleTree, $permissionName,  $userRoleNames,'owner');
            }
        }
        return false;
    }


    public function checkPermission($attribute, $entity, $user)
    {

        


        
        

        $userRoleNames=$this->getUserRoleNames($user);
        
        $objectName=$this->nameExtractor->getEntityName($entity);

        
        if (!array_key_exists($objectName,$this->objectScopePermissionRoleTree)) return true;
        
        $scopePermissionRoleTree=$this->objectScopePermissionRoleTree[$objectName];
        
        if ($this->checkListPermission($scopePermissionRoleTree,$attribute,  $userRoleNames)) return true;
        

        if ($this->checkPermissionScopeAll($scopePermissionRoleTree,$attribute,  $userRoleNames)) return true;

        if ($this->checkPermissionScopeGroup($user,$entity,$scopePermissionRoleTree,$attribute,  $userRoleNames)) return true;
    
        if ($this->checkPermissionScopeOwner($user,$entity,$scopePermissionRoleTree,$attribute,  $userRoleNames)) return true;

        return false;
        
    }

}
