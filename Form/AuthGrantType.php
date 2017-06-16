<?php

namespace triguk\AuthorizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AuthGrantType extends AbstractType 
{
    private $bundleName,$userEntity;
    
    public function __construct($userEntity,$bundleName)
    {
        $this->bundleName=$bundleName;
        $this->userEntity=$userEntity;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authRoleEditEnabled=array_key_exists('selectBoxesDisabled',$options)? 
            ($options['selectBoxesDisabled']== false) : true;
        $authObjectEditEnabled=$authRoleEditEnabled;
        $authScopeEditEnabled=$authRoleEditEnabled;
        $builder->add('authRole', EntityType::class, array(
            // query choices from this entity
            'class' => "{$this->bundleName}:AuthRole",

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
             'multiple' => false,
             'expanded' => false,
             'disabled' => !$authRoleEditEnabled,
        ))
        ->add('authObject', EntityType::class, array(
            // query choices from this entity
            'class' => "{$this->bundleName}:AuthObject",

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
             'multiple' => false,
             'expanded' => false,
             'disabled' => !$authObjectEditEnabled,
        ))
        ->add('authScope', EntityType::class, array(
            // query choices from this entity
            'class' => "{$this->bundleName}:AuthScope",

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
             'multiple' => false,
             'expanded' => false,
             'disabled' => !$authScopeEditEnabled,
        ))        
        ->add('authPermission', EntityType::class, array(
            // query choices from this entity
            'class' => "{$this->bundleName}:AuthPermission",

            // use the User.username property as the visible option string
            'choice_label' => 'name',

            // used to render a select box, check boxes or radios
             'multiple' => true,
             'expanded' => true,
        ));
        //die("{$this->bundleName}:AuthPermission");
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null //'triguk\AuthorizationBundle\Entity\AuthGrant'
        ));
    }
}
