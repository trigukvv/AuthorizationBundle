<?php
namespace triguk\AuthorizationBundle\Helper;
use Doctrine\ORM\EntityManagerInterface;


class ORMEntityNameExtractor implements EntityNameExtractor
{
    
    protected $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;
    }
    
    public function getEntityName($entity)
    {
        try 
        {
            if (is_string($entity))
            {
                $entityClassName =$entity;
            }
            else
            {
                $entityClassName = $this->manager->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
            }
            if (strpos($entityClassName,':')===false) 
            {
                $path = explode('\Entity\\', $entityClassName);
                return str_replace('\\', '', $path[0]).':'.$path[1];
            }
            else
            {
                return $entityClassName;     
            }
            
        } 
        catch (MappingException $e) 
        {
            throw new \InvalidArgumentException('Given object ' . get_class($entity) . ' is not a Doctrine Entity. ');
        }

        return $entityName;
    }
    
}
