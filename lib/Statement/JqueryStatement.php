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
 * Description of JqueryStatement
 *
 * @package ozy
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class JqueryStatement extends AbstractStatement{
	private $_selector;
	private $_chain;
	
	public function __construct($selector, $environment) {
		parent::__construct($environment);
		$this->_selector = $selector;
		$this->_chain = new \SplQueue();
	}

	protected function getName() {
		return $this->_environment == 'dev' ? 'j' : 'jquery';
	}
	
	public function __call($name, $arguments) {
		$pair = new \stdClass();
		$nameProp = $this->_environment == 'dev' ? 'n' : 'name';
		$paramsProp = $this->_environment == 'dev' ? 'p' : 'parameters';
		
		$pair->$$nameProp = $name;
		$pair->$$paramsProp = $arguments;
		$this->_chain->enqueue($pair);
	}
}