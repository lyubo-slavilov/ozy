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
 * Represents the javascript function statement.
 * Example:
 * <code>
 * function foo(bar, baz){
 *	// function body
 * }
 * </code>
 * 
 * @package ozy
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class FunctionStatement extends AbstractStatement{
		
	public function __construct($name, $arguments, $body, $environment) {
		
		parent::__construct($environment);
		
		$isDev = $this->_environment == 'dev';
		
		$nameProp = $isDev ? 'name' : 'n';
		$argumentsProp = $isDev ? 'arguments' : 'a';
		$bodyProp = $isDev ? 'body' : 'b';
		
		$this->_jsonStructure->$$nameProp = $name;
		$this->_jsonStructure->$$argumentsProp = $arguments;
		$this->_jsonStructure->$$bodyProp = $body;
		
	}

	protected function getName() {
		return $this->_environment == 'dev' ? 'function' : 'f';
	}
}