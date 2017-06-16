<?php
namespace Tests\triguk\AuthorizationBundle\Security;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use triguk\AuthorizationBundle\Security\ORMPermissionChecker;
use triguk\AuthorizationBundle\Helper\EntityNameExtractor;
use triguk\AuthorizationBundle\Helper\ORMEntityNameExtractor;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Repository\AuthGrantRepositoryInterface;
use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Entity\AuthPermission;
use triguk\AuthorizationBundle\Entity\AuthScope;
use triguk\AuthorizationBundle\Entity\HasAuthRoles;
use triguk\AuthorizationBundle\Entity\HasGroups;
use triguk\AuthorizationBundle\Entity\HasOwner;

interface HasId
{
    public function getId();
}

interface HasAuthRolesAndId extends HasId,HasAuthRoles
{
}
interface HasAuthRolesGroupsAndId extends HasId,HasAuthRoles,HasGroups
{
}

class ORMPermissionCheckerTest extends TestCase
{
    private function getAuthPermissions()
    {
        $permissions=['create','read','update','delete'];
        $authPermissionMocks=[];
        
        foreach ($permissions as $permission)
        {
        
            $authPermissionMock=$this
                ->getMockBuilder(AuthPermission::class)
                ->disableOriginalConstructor()
                ->getMock();
            
            $authPermissionMock
                ->method('getName')  
                ->willReturn($permission);
                
            $authPermissionMocks[]=$authPermissionMock;
         
        }

         
                        
        return $authPermissionMocks;
    }
    
    private function getAuthObjects()
    {
        $objects=['AppBundle:User','AppBundle:Article','AppBundle:Comment'];
        $authObjectMocks=[];
        
        foreach ($objects as $object)
        {
        
            $authObjectMock=$this
                ->getMockBuilder(AuthObject::class)
                ->disableOriginalConstructor()
                ->getMock();
            
            $authObjectMock
                ->method('getName')  
                ->willReturn($object);
                
            $authObjectMocks[]=$authObjectMock;
         
        }

         
                        
        return $authObjectMocks;
    }
    
    private function getAuthRoles()
    {
        
        $roles=['admin','moderator','user'];
        $authRoleMocks=[];
        
        foreach ($roles as $role)
        {
        
            $authRoleMock=$this
                ->getMockBuilder(AuthRole::class)
                ->disableOriginalConstructor()
                ->getMock();
            
            $authRoleMock
                ->method('getName')  
                ->willReturn($role);   
                
            $authRoleMocks[]=$authRoleMock;
        }
            
        
            
            
        return $authRoleMocks;
    }


    private function getAuthScopes()
    {
        $scopes=['all','group','owner'];
        $authScopeMocks=[];
        
        foreach ($scopes as $scope)
        {
        
            $authScopeMock=$this
                ->getMockBuilder(AuthScope::class)
                ->disableOriginalConstructor()
                ->getMock();
            
            $authScopeMock
                ->method('getName')  
                ->willReturn($scope);   
                
            $authScopeMocks[]=$authScopeMock;
        }
            
        
            
            
        return $authScopeMocks;
    }
    private function getAuthGrants()
    {
        $roles=$this->getAuthRoles();
        $roleAdmin=$roles[0];
        $roleModerator=$roles[1];
        $roleUser=$roles[2];
        
        $scopes=$this->getAuthScopes();
        $scopeAll=$scopes[0];
        $scopeGroup=$scopes[1];
        $scopeOwner=$scopes[2];
        
        $permissions=$this->getAuthPermissions();
        $objects=$this->getAuthObjects();
        
        $authGrants=[];
        
        // all permissions for all objects with scope 'all' for roleAdmin:
        foreach ($permissions as $permission)
        {
            foreach ($objects as $object)
            {
                $authGrantMock=$this
                    ->getMockBuilder(AuthGrant::class)
                    ->disableOriginalConstructor()
                    ->getMock();
                    
                $authGrantMock
                    ->method('getAuthRole')
                    ->willReturn($roleAdmin);

                $authGrantMock
                    ->method('getAuthScope')
                    ->willReturn($scopeAll);   
                                 
                $authGrantMock
                    ->method('getAuthPermission')
                    ->willReturn($permission);
                
                $authGrantMock
                    ->method('getAuthObject')
                    ->willReturn($object);
                    
                $authGrants[]=$authGrantMock;
            }
        }
        
        // all permissions for Article with scope 'owner' for roleUser:
        foreach ($permissions as $permission)
        {
            $object=$objects[1];
            $authGrantMock=$this
                ->getMockBuilder(AuthGrant::class)
                ->disableOriginalConstructor()
                ->getMock();
                
            $authGrantMock
                ->method('getAuthRole')
                ->willReturn($roleUser);

            $authGrantMock
                ->method('getAuthScope')
                ->willReturn($scopeOwner);   
                             
            $authGrantMock
                ->method('getAuthPermission')
                ->willReturn($permission);
            
            $authGrantMock
                ->method('getAuthObject')
                ->willReturn($object);
                
            $authGrants[]=$authGrantMock;
        }
        
        // all permissions for Article with scope 'group' for roleModerator:
        foreach ($permissions as $permission)
        {
            $object=$objects[1];
            $authGrantMock=$this
                ->getMockBuilder(AuthGrant::class)
                ->disableOriginalConstructor()
                ->getMock();
                
            $authGrantMock
                ->method('getAuthRole')
                ->willReturn($roleModerator);

            $authGrantMock
                ->method('getAuthScope')
                ->willReturn($scopeGroup);   
                             
            $authGrantMock
                ->method('getAuthPermission')
                ->willReturn($permission);
            
            $authGrantMock
                ->method('getAuthObject')
                ->willReturn($object);
                
            $authGrants[]=$authGrantMock;
        }
        
        // create, read for Article with scope 'all' for roleUser:
        foreach ([$permissions[0],$permissions[1]] as $permission)
        {
            $object=$objects[1];
            $authGrantMock=$this
                ->getMockBuilder(AuthGrant::class)
                ->disableOriginalConstructor()
                ->getMock();
                
            $authGrantMock
                ->method('getAuthRole')
                ->willReturn($roleUser);

            $authGrantMock
                ->method('getAuthScope')
                ->willReturn($scopeAll);   
                             
            $authGrantMock
                ->method('getAuthPermission')
                ->willReturn($permission);
            
            $authGrantMock
                ->method('getAuthObject')
                ->willReturn($object);
                
            $authGrants[]=$authGrantMock;
        }
        

        return $authGrants;
            
    }    
    private function getAuthObjectRepository()
    {
        $objectRepositoryMock=$this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $objectRepositoryMock
            ->method('findAll')    
            ->willReturn($this->getAuthObjects());
        
        return $objectRepositoryMock;
    }
    
    private function getAuthRoleRepository()
    {
        $objectRepositoryMock=$this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $objectRepositoryMock
            ->method('findAll')    
            ->willReturn($this->getAuthRoles());
        
        return $objectRepositoryMock;
    }    
    private function getAuthScopeRepository()
    {
        $scopeRepositoryMock=$this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $scopeRepositoryMock
            ->method('findAll')    
            ->willReturn($this->getAuthScopes());
            
        return $scopeRepositoryMock;
    }
    
    private function getAuthPermissionRepository()
    {
        $permissionRepositoryMock=$this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $permissionRepositoryMock
            ->method('findAll')    
            ->willReturn($this->getAuthPermissions());
            
        return $permissionRepositoryMock;
    }
    private function getAuthGrantRepository()
    {
        $grantRepositoryMock=$this
            ->getMockBuilder(AuthGrantRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

            
        $grantRepositoryMock
            ->method('getAllGrants')    
            ->willReturn($this->getAuthGrants());
        //echo "Asked for GrantRepositoryMock\n";
        return $grantRepositoryMock;
    }    
    private function getNameExtractor()
    {
        $nameExtractorMock=$this
            ->getMockBuilder(ORMEntityNameExtractor::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $nameExtractorMock
            ->method('getEntityName')  
            ->willReturn('AppBundle:Article');    
            
        return $nameExtractorMock;
    }
    
    private function getEntityManager()
    {
        $manager=$this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $map=
        [
            [AuthObject::class,$this->getAuthObjectRepository()],
            [AuthRole::class,$this->getAuthRoleRepository()],
            [AuthPermission::class,$this->getAuthPermissionRepository()],
            [AuthScope::class,$this->getAuthScopeRepository()],
            [AuthGrant::class, $this->getAuthGrantRepository()]
        ];
        $manager->method('getRepository')  
            ->will($this->returnValueMap($map));
            
        return $manager;
    }
    
    private function getAdminUser()
    {
        $adminUserMock=$this
            ->getMockBuilder(HasAuthRolesAndId::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $adminUserMock
            ->method('getAuthRoles')    
            ->willReturn([$this->getAuthRoles()[0]]);      
            
        return $adminUserMock;
    }
    
    private function getUser($id)
    {
        $user=$this
            ->getMockBuilder(HasAuthRolesAndId::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $user
            ->method('getAuthRoles')    
            ->willReturn([]);       
        $user
            ->method('getId')    
            ->willReturn($id);  
            
        return $user;
    }
    private function getArticleOwnedBy($userId)
    {
        $article=$this
            ->getMockBuilder(HasOwner::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $article
            ->method('getOwner')    
            ->willReturn($this->getUser($userId));   
            
        return $article;
    }   
    
    
    private function createTestEnvironment()
    {
        $manager=$this->getEntityManager();
        
        $nameExtractor=$this->getNameExtractor();
        
        return  [
                    'manager'=>$manager,
                    'nameExtractor'=>$nameExtractor
                ];
    }
    
    private function createSubject()
    {
        
        $testEnvironment=$this->createTestEnvironment();
        
        $checker=new ORMPermissionChecker(
            $testEnvironment['manager'],
            $testEnvironment['nameExtractor']
        );
        
        return $checker;
    }
    
    public function testGrantsScopeAll()
    {
        $checker=$this->createSubject();
        
        $adminUser=$this->getAdminUser();
        $article7=$this->getArticleOwnedBy(7);
        
        $this->assertTrue($checker->checkPermission('create', $article7, $adminUser));
        $this->assertTrue($checker->checkPermission('read', $article7, $adminUser));
        $this->assertTrue($checker->checkPermission('update', $article7, $adminUser));
        $this->assertTrue($checker->checkPermission('delete', $article7, $adminUser));
        $this->assertFalse($checker->checkPermission('edit', $article7, $adminUser));
    }
    
    public function testGrantScopeOwner()
    {
        $checker=$this->createSubject();
        
        $user5=$this->getUser(5);
        $user6=$this->getUser(6);
        $user7=$this->getUser(7);
        $article7=$this->getArticleOwnedBy(7);
        
        
        $this->assertTrue($checker->checkPermission('create', $article7, $user5));
        $this->assertTrue($checker->checkPermission('read', $article7, $user5));
        $this->assertFalse($checker->checkPermission('update', $article7, $user5));
        $this->assertFalse($checker->checkPermission('delete', $article7, $user5));

        $this->assertTrue($checker->checkPermission('create', $article7, $user6));
        $this->assertTrue($checker->checkPermission('read', $article7, $user6));
        $this->assertFalse($checker->checkPermission('update', $article7, $user6));
        $this->assertFalse($checker->checkPermission('delete', $article7, $user6));
            
        $this->assertTrue($checker->checkPermission('create', $article7, $user7));
        $this->assertTrue($checker->checkPermission('read', $article7, $user7));
        $this->assertTrue($checker->checkPermission('update', $article7, $user7));
        $this->assertTrue($checker->checkPermission('delete', $article7, $user7));
        
        //$this->assertFalse($checker->checkPermission('edit', $article7, $adminUser));
    }
    
    private function getGroup($id)
    {
        $group=$this
            ->getMockBuilder(HasId::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $group
            ->method('getId')    
            ->willReturn($id);   
        
        return $group;
      
    }
    
    private function getArticleWithGroup($groupId)
    {
        $article=$this
            ->getMockBuilder(HasGroups::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $article
            ->method('getGroups')    
            ->willReturn([$this->getGroup($groupId)]);   
            
        return $article;
    }
    
    
    private function getModeratorForGroups($id,$groupIds)
    {
        $user=$this
            ->getMockBuilder(HasAuthRolesGroupsAndId::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $user
            ->method('getAuthRoles')    
            ->willReturn([$this->getAuthRoles()[1]]);        
        $user
            ->method('getGroups')    
            ->willReturn($groupIds);
                           
        $user
            ->method('getId')    
            ->willReturn($id);  
            
        return $user;        
    }
    
    public function testGrantsScopeGroup()
    {
        $checker=$this->createSubject();
        
        $moderatorGroups=[40,42];
        $moderator=$this->getModeratorForGroups(10,$moderatorGroups);
        $articleGroups=[40,40,41,42,43,43];
        $articles=[];
        foreach ($articleGroups as $articleGroup)
        {
            $articles[]=$this->getArticleWithGroup($articleGroup);
            
        }
        foreach ($articles as $article)
        {
            $this->assertEquals(
                $checker->checkPermission('update', $article, $moderator),
                in_array($article->getGroups()[0]->getId(),$moderatorGroups)
            );
                
        }
    }
    
    public function Sample()
    {

        
        

        
        $this->assertInstanceOf(ORMPermissionChecker::class,$checker);
        
        $adminUser=$this->getAdminUser();

        
        $article7=$this->getArticleOwnedBy(7);
        
        $this->assertInstanceOf(HasAuthRoles::class,$adminUser);
        
        $this->assertTrue($checker->checkPermission('update', $article7, $adminUser));
            
    }
}
