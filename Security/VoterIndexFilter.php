<?php

namespace triguk\AuthorizationBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use triguk\AuthorizationBundle\Security\IndexFilter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManager;

class VoterIndexFilter implements IndexFilter
{
    protected $authorizationChecker;
    
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }


    
    public function filter($permission, $objects)
    {
        $newObjectList=[];
        foreach ($objects as $object)
        {
            if ($this->authorizationChecker->isGranted('read', $object))
                $newObjectList[]=$object;
        }
        return $newObjectList;
    }
    
}
