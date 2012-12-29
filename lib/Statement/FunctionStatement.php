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
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class FunctionStatement extends AbstractStatement{
		
	public function __construct($name, $arguments, $body) {
		
		parent::__construct();
		
		$this->_jsonStructure->name = $name;
		$this->_jsonStructure->arguments = $arguments;
		$this->_jsonStructure->body = $body;
		
	}

	protected function getName($environment = 'dev') {
		return $environment == 'dev' ? 'function' : 'f';
	}
}