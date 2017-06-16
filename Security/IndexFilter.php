<?php

namespace triguk\AuthorizationBundle\Security;


interface IndexFilter
{
    public function filter($permission, $objects);
    
}
