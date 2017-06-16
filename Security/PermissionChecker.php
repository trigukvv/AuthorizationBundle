<?php

namespace triguk\AuthorizationBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManager;

interface PermissionChecker
{
    public function getSupportedPermissionNames();
    public function checkPermission($attribute, $object, $user);
    
}
