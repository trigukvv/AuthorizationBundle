<?php

namespace triguk\AuthorizationBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class IsGrantedTwigExtension extends \Twig_Extension
{
    protected $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

	public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isGranted', array($this, 'isGranted')),
        ];
    }
    
    public function isGranted($attribute, $object)
    {
        return $this->authorizationChecker->isGranted($attribute,$object);
    }


}
