<?php

namespace triguk\AuthorizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthGrant
 *
 * @ORM\Table(name="auth_grants")
 * @ORM\Entity(repositoryClass="triguk\AuthorizationBundle\Repository\AuthGrantRepository")
 */
class AuthGrant
{


    /**
     * @ORM\ManyToOne(targetEntity="AuthRole", inversedBy="authGrants")
     * @ORM\Id
     * @ORM\JoinColumn(name="auth_role_id", referencedColumnName="id", nullable=false)
     */
    private $authRole;
    
    /**
     * @ORM\ManyToOne(targetEntity="AuthPermission")
     * @ORM\Id
     * @ORM\JoinColumn(name="auth_permission_id", referencedColumnName="id", nullable=false)
     */
    private $authPermission;    

    /**
     * @ORM\ManyToOne(targetEntity="AuthScope")
     * @ORM\Id
     * @ORM\JoinColumn(name="auth_scope_id", referencedColumnName="id", nullable=false)
     */
    private $authScope;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AuthObject")
     * @ORM\Id
     * @ORM\JoinColumn(name="auth_object_id", referencedColumnName="id", nullable=false)
     */
    private $authObject;     


    /**
     * Set authRole
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthRole $authRole
     *
     * @return AuthGrant
     */
    public function setAuthRole(\triguk\AuthorizationBundle\Entity\AuthRole $authRole)
    {
        $this->authRole = $authRole;

        return $this;
    }

    /**
     * Get authRole
     *
     * @return \triguk\AuthorizationBundle\Entity\AuthRole
     */
    public function getAuthRole()
    {
        return $this->authRole;
    }

    /**
     * Set authPermission
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthPermission $authPermission
     *
     * @return AuthGrant
     */
    public function setAuthPermission(\triguk\AuthorizationBundle\Entity\AuthPermission $authPermission)
    {
        $this->authPermission = $authPermission;

        return $this;
    }

    /**
     * Get authPermission
     *
     * @return \triguk\AuthorizationBundle\Entity\AuthPermission
     */
    public function getAuthPermission()
    {
        return $this->authPermission;
    }

    /**
     * Set authScope
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthScope $authScope
     *
     * @return AuthGrant
     */
    public function setAuthScope(\triguk\AuthorizationBundle\Entity\AuthScope $authScope)
    {
        $this->authScope = $authScope;

        return $this;
    }

    /**
     * Get authScope
     *
     * @return \triguk\AuthorizationBundle\EntityScope
     */
    public function getAuthScope()
    {
        return $this->authScope;
    }

    /**
     * Set authObject
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthObject $authObject
     *
     * @return AuthGrant
     */
    public function setAuthObject(\triguk\AuthorizationBundle\Entity\AuthObject $authObject)
    {
        $this->authObject = $authObject;

        return $this;
    }

    /**
     * Get authObject
     *
     * @return \triguk\AuthorizationBundle\Entity\AuthObject
     */
    public function getAuthObject()
    {
        return $this->authObject;
    }
}
