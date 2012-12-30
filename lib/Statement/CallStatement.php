<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ozy\Statement;
/**
 * Represents the javascript function call statement.
 * Example:
 * <code>
 *  foo(2, 5, 'bar');
 * </code>
 *
 * @package ozy
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class CallStatement extends AbstractStatement{

	public function __construct($name, $parameters, $environment) {
		parent::__construct($environment);
		
		$isDev = $this->_environment == 'dev';
		
		$nameProp = $isDev ? 'name' : 'n';
		$parametersProp = $isDev ? 'parameters' : 'p';
		
		$this->_jsonStructure->$$nameProp = $name;
		$this->_jsonStructure->$$parametersProp = $arguments;
		
	}

	protected function getName() {
		return $this->_environment == 'dev' ? 'c' : 'call';
	}
}