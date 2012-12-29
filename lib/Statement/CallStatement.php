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
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class CallStatement extends AbstractStatement{

	public function __construct($name, $parameters) {
		parent::__construct();
		$this->_jsonStructure->name = $name;
		$this->_jsonStructure->parameters = $parameters;
	}

	protected function getName($environment = 'dev') {
		$environment == 'dev' ? 'c' : 'call';
	}
}