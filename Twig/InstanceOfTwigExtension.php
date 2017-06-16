<?php

namespace triguk\AuthorizationBundle\Twig;

class InstanceOfTwigExtension extends \Twig_Extension
{

	public function getTests()
	{
		return [
			new \Twig_SimpleTest ('instanceof', [$this, 'isInstanceof'])
		];
	}

	/**
	 * @param $var
	 * @param $instance
	 * @return bool
	 */
	public function isInstanceof($var, $instance) {
		return  $var instanceof $instance;
	}
}
