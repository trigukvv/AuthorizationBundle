<?php

namespace triguk\AuthorizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthRole
 *
 * @ORM\Table(name="auth_roles")
 * @ORM\Entity(repositoryClass="triguk\AuthorizationBundle\Repository\AuthRoleRepository")
 */
class AuthRole
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AuthGrant", mappedBy="authRole")
     */
    private $authGrants;

    /**
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\User", mappedBy="authRoles")
     */
    private $users;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AuthRole
     */
    public function setTitle($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AuthRole
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AuthRole
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authGrants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add authGrant
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthGrant $authGrant
     *
     * @return AuthRole
     */
    public function addAuthGrant(\triguk\AuthorizationBundle\Entity\AuthGrant $authGrant)
    {
        $this->authGrants[] = $authGrant;

        return $this;
    }

    /**
     * Remove authGrant
     *
     * @param \triguk\AuthorizationBundle\Entity\AuthGrant $authGrant
     */
    public function removeAuthGrant(\triguk\AuthorizationBundle\Entity\AuthGrant $authGrant)
    {
        $this->authGrants->removeElement($authGrant);
    }

    /**
     * Get authGrants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthGrants()
    {
        return $this->authGrants;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return AuthRole
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    public function __toString()
    {
        return $this->getName().' ('.$this->getDescription().')';
    }
}
