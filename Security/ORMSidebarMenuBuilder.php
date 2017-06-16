<?php

namespace triguk\AuthorizationBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ORMSidebarMenuBuilder implements SidebarMenuBuilder
{

    protected $manager,$router,$authorizationChecker;
    
    private $grantRepository,$scopeRepository,$permissionRepository,$roleRepository,$objectRepository;
    private $objects, $objectNames;
    
    
    
    
    public function __construct(EntityManager $manager, Router $router, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->manager=$manager;
        $this->router=$router;
        $this->authorizationChecker=$authorizationChecker;
        $this->grantRepository=$manager->getRepository('trigukAuthorizationBundle:AuthGrant');
        $this->objectRepository=$manager->getRepository('trigukAuthorizationBundle:AuthObject');  
        $this->objects = $this->objectRepository->findAll();  
    }    
    private function buildObjectNames()
    {
        $objectNames=[];
        foreach ($this->objects as $object)
        {
            $objectNames[$object->getName()]=
                [
                    'titlePlural'=>$object->getTitlePlural(),
                    'titleSingular'=>$object->getTitleSingular(),
                    'description'=>$object->getDescription()
                ];
        }
        $this->objectNames=$objectNames;
    }
    public function getObjectNames()
    {
        return $this->objectNames;
    }
    
    public function buildMenu($permission='listAll',$routePrefix='')
    {


        
        //$objects = $this->objectRepository->findByName('AppBundle:Client');   
        $listItems=[];
        
        foreach ($this->objects as $object)
        {
            //echo $object->getName();
            if ($this->authorizationChecker->isGranted($permission,$object->getName()))
            {
                
                $route=$object->getRoute();
                $path='#';
                try
                {
                    $realRoute=$routePrefix.$route;
                    //var_dump($realRoute);
                    $path=$this->router->generate($realRoute);


                }
                catch (RouteNotFoundException $e)
                {
                    
                }
                    $listItems[]=
                    [
                        'title'=>$object->getTitlePlural(),
                        'path'=>$path
                    ];                
                
            }
        }
        
        return $listItems;
        
    }

    


}
