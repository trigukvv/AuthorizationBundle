<?php

namespace triguk\AuthorizationBundle\Security;


interface SidebarMenuBuilder
{
    public function getObjectNames();
    
    public function buildMenu($permission,$routePrefix);
    
}
