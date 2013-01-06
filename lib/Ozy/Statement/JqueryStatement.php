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
	private $_props;
	
	
	public function __construct($selector, $environment) {
		parent::__construct($environment);
		$this->_selector = $selector;
		$this->_chain = new \SplQueue();
		
		$isDev = $this->_environment == 'dev';
		$this->_props = new \stdClass();
		$this->_props->method = $isDev ? 'method' : 'm';
		$this->_props->parameters = $isDev ? 'parameters' : 'p';
	}

	public function getName() {
		return $this->_environment == 'dev' ? 'jquery' : 'j';
	}
	
		
	public function __call($method, $arguments) {
		$pair = new \stdClass();
		$pair->{$this->_props->method} = $method;
		
		if($arguments[count($arguments)-1] == 'engine-call'){
			//this is external call
			$pair->{$this->_props->parameters} = $arguments[0];
		}else{
			$pair->{$this->_props->parameters} = $arguments;
		}
		
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