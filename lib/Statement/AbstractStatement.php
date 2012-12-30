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
 * Abstraction for all Ozy statements.
 * 
 * Subclasses have to implement the following abstract methods:
 *  
 *  - getName() - this will be used for naming the statement for later js recognition
 *
 * @package ozy
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
abstract class AbstractStatement {
	
	protected $_jsonStructure;
	protected $_environment;
	
	/**
	 * Constructor - initializes the $_jsonStructure property.
	 */
	public function __construct($environment) {
		$this->_environment = $environment;
		$this->_jsonStructure = new \stdClass();
	}
	/**
	 * Called before json_encode(). Here each subclass will change the
	 * jsonStructure representing the statement
	 * 	 */
	protected function prepareJsonStructure();
	
	/**
	 * @return \stdClass
	 */
	protected function getJsonStructure(){
		$this->prepareJsonStructure();
		return $this->_jsonStructure;
	}
	
	/**
	 * @abstract
	 */
	abstract protected function getName($environment = 'dev');
}