<?php
namespace Tests\triguk\AuthorizationBundle\Security;

use triguk\AuthorizationBundle\Entity\AuthObject;
use triguk\AuthorizationBundle\Entity\AuthPermission;
use triguk\AuthorizationBundle\Entity\AuthGrant;
use triguk\AuthorizationBundle\Entity\AuthRole;
use triguk\AuthorizationBundle\Entity\AuthScope;
use PHPUnit\Framework\TestCase;
use triguk\AuthorizationBundle\Helper\ORMEntityNameExtractor;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvalidEntity
{
    public function getId()
    {
        return -1;
    }
}

class ORMEntityNameExtractorTest extends KernelTestCase
{
    private $mnager;
    
    public function __construct()
    {
        parent::__construct();
        
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
            
              
    }

    public function testNameExtractorCorrectBehavior()
    {
        $extractor=new ORMEntityNameExtractor($this->manager);  
        
        $authGrant=new AuthGrant();
        
        $this->assertEquals($extractor->getEntityName($authGrant),'trigukAuthorizationBundle:AuthGrant');
        $this->assertEquals($extractor->getEntityName(new AuthObject()),'trigukAuthorizationBundle:AuthObject');
        $this->assertEquals($extractor->getEntityName(AuthRole::class),'trigukAuthorizationBundle:AuthRole');
        
        
    }
    
    public function testNameExtractorException()
    {
        $this->expectException(\Exception::class);
        
        $extractor=new ORMEntityNameExtractor($this->manager);  
        $entity=new InvalidEntity;
        $this->assertEquals($extractor->getEntityName($entity),'trigukAuthorizationBundle:AuthRole');
    }
}
