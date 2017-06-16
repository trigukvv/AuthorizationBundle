<?php

namespace triguk\AuthorizationBundle\Formatter;

class AuthGrantFormatter
{

    public function groupAuthGrants($items,$sortColumn=0)
    {
        $entityGroups=[];
        
        foreach ($items as $item)
        {
            $authRoleName=$item->getAuthRole()->getName();
            $authObjectName=$item->getAuthObject()->getId();
            $authScopeName=$item->getAuthScope()->getName();
            $authPermissionName=$item->getAuthPermission()->getName();
            switch ($sortColumn)
            {
                case 0:
                    $key="$authRoleName/$authObjectName/$authScopeName";
                    break;
                case 1:
                    $key="$authObjectName/$authRoleName/$authScopeName";
                    break;
                case 2:
                    $key="$authScopeName/$authRoleName/$authObjectName";
                    break;
                default:
                    $key="$authRoleName/$authObjectName/$authScopeName";
            }
            //var_dump($key);
            if (!array_key_exists($key,$entityGroups))
            {
                $entityGroups[$key]=[];
            }
            $entityGroups[$key][]=$item;
        }
        ksort($entityGroups);
        $lines=[];
        
        foreach ($entityGroups as $entityGroup)
        {
            $authPermissions='';
            $authRoleName='';
            $authObjectName='';
            $authScopeName='';
            foreach ($entityGroup as $entity)
            {
                {
                    $authRole=$entity->getAuthRole();
                    $authObject=$entity->getAuthObject();
                    $authScope=$entity->getAuthScope(); 
                    $authRoleName=$entity->getAuthRole()->getName();
                    $authObjectName=$entity->getAuthObject()->getName();
                    $authScopeName=$entity->getAuthScope()->getName(); 
                    
                }
                if (strlen($authPermissions)==0)
                {
                    $authPermissions=$entity->getAuthPermission()->getName();
                    $authRoleName=$entity->getAuthRole()->getName();
                    $authObjectName=$entity->getAuthObject()->getName();
                    $authScopeName=$entity->getAuthScope()->getName();  
                    //var_dump("$authRoleName/$authObjectName/$authScopeName");
                }
                else
                {
                    $authPermissions=$authPermissions.', '.$entity->getAuthPermission()->getName();

                }
            }
            $lines[]=[$authRoleName,$authObjectName,$authScopeName,$authPermissions,$authRole->getId(),$authObject->getId(),$authScope->getId()];
        }
        
        return $lines;
    }
    

}
