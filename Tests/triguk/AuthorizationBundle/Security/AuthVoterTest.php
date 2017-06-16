<?php
namespace Tests\triguk\AuthorizationBundle\Security;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use triguk\AuthorizationBundle\Security\PermissionChecker;
use triguk\AuthorizationBundle\Security\AuthVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class AuthVoterTest extends TestCase
{
    public static function callMethod($obj, $name, array $args) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    public function testAuthVoter()
    {
        $permissionCheckerMock=$this
            ->getMockBuilder(PermissionChecker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $permissionCheckerMock
            ->method('getSupportedPermissionNames')    
            ->willReturn(['create','read','update','delete']);

        $permissionCheckerMock
            ->method('checkPermission')    
            ->willReturn(true);
                    
        $voter=new AuthVoter($permissionCheckerMock);
        
        $this->assertEquals(self::callMethod($voter,'supports',['read',null]),true);
        $this->assertEquals(self::callMethod($voter,'supports',['edit',null]),false);
        
        
        $tokenMock=$this
            ->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->assertEquals(self::callMethod($voter,'voteOnAttribute',['edit',null,$tokenMock]),true);
        
    }
}
