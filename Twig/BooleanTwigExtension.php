<?php

namespace triguk\AuthorizationBundle\Twig;

class BooleanTwigExtension extends \Twig_Extension
{
	public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('boolean', array($this, 'booleanFilter')),
        ];
    }
    
    public function booleanFilter($value)
    {
        if ($value) 
        {
            return "Да";
        } 
        else 
        {
            return "Нет";
        }
    }

	public function getTests()
	{
		return [
			 new \Twig_SimpleTest('bool_value', [$this, 'isBool'])
		];
	}

	/**
	 * @param $var
	 * @param $instance
	 * @return bool
	 */
	public function isBool($var) {
		return  is_bool($var);
	}
}
