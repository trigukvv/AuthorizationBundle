<?php

namespace triguk\AuthorizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AuthObject
 *
 * @ORM\Table(name="auth_objects")
 * @ORM\Entity(repositoryClass="triguk\AuthorizationBundle\Repository\AuthObjectRepository")
 */
class AuthObject
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title_singular", type="string", length=255, nullable=true)
     */
    private $titleSingular;

    /**
     * @var string
     *
     * @ORM\Column(name="title_plural", type="string", length=255, nullable=true)
     */
    private $titlePlural;
    
    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    private $route;    
    
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
     * @return AuthObject
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
     * Set description
     *
     * @param string $description
     *
     * @return AuthObject
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
    
    public function __toString()
    {
        return $this->getName().' ('.$this->getDescription().')';
    }
    

    /**
     * Set titleSingular
     *
     * @param string $titleSingular
     *
     * @return AuthObject
     */
    public function setTitleSingular($titleSingular)
    {
        $this->titleSingular = $titleSingular;

        return $this;
    }

    /**
     * Get titleSingular
     *
     * @return string
     */
    public function getTitleSingular()
    {
        return $this->titleSingular;
    }



    /**
     * Set titlePlural
     *
     * @param string $titlePlural
     *
     * @return AuthObject
     */
    public function setTitlePlural($titlePlural)
    {
        $this->titlePlural = $titlePlural;

        return $this;
    }

    /**
     * Get titlePlural
     *
     * @return string
     */
    public function getTitlePlural()
    {
        return $this->titlePlural;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return AuthObject
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}
