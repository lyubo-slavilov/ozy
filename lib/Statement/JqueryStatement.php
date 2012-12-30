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
class JqueryStatement extends \Ozy\Statement{
	private $_selector;
	private $_chain;
	
	public function __construct($selector, $environment) {
		parent::__construct($environment);
		$this->_selector = $selector;
		$this->_chain = new \SplQueue();
	}

	public function getName() {
		return $this->_environment == 'dev' ? 'jquery' : 'j';
	}
	
	public function __call($name, $arguments) {
		$pair = new \stdClass();
		$nameProp = $this->_environment == 'dev' ? 'name' : 'n';
		$paramsProp = $this->_environment == 'dev' ? 'parameters' : 'p';
		
		$pair->{$nameProp} = $name;
		$pair->{$paramsProp} = $arguments;
		$this->_chain->enqueue($pair);
	}
	
	protected function prepareJsonStructure() {
		$structure = new \stdClass();
		$selectorProp = $this->_environment == 'dev' ? 'selector' : 's';
		$chainProp = $this->_environment == 'dev' ? 'chain' : 'c';
		$structure->{$selectorProp} = $this->_selector;
		$structure->{$chainProp} = array();
		foreach($this->_chain as $pair){
			$structure->{$chainProp}[] = $pair;
		}
		$this->_jsonStructure = $structure;
	}
}