<?php

namespace triguk\AuthorizationBundle\Repository;
use triguk\AuthorizationBundle\Repository\SimplePaginatorInterface;
use triguk\AuthorizationBundle\Repository\SimplePaginatorTrait;
   
/**
 * AuthPermissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AuthPermissionRepository extends \Doctrine\ORM\EntityRepository  implements SimplePaginatorInterface
{
    use SimplePaginatorTrait;    
}
