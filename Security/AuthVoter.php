<?php

namespace triguk\AuthorizationBundle\Security;


use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Doctrine\ORM\EntityManager;
use triguk\AuthorizationBundle\Security\PermissionChecker;


class AuthVoter extends Voter
{
    protected $checker;
    
    public function __construct(PermissionChecker $checker)
    {
        $this->checker=$checker;
  
    } 
    
    protected function supports($attribute, $subject)
    {
        $supportedPermissions=$this->checker->getSupportedPermissionNames();
        //var_dump($supportedPermissions);
        return in_array($attribute,$supportedPermissions);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $scopeAll='all';
        
        return $this->checker->checkPermission($attribute,$subject,$user);
    }


}
