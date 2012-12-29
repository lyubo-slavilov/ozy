<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ozy;

use Ozy\Statement\AbstractStatement;

/**
 * Description of Ozy
 *
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class Manager {
	
	/**
	 * @var \SplQueue 
	 */
	private $_statementQueue;
	
	private $_environment;
	
	private $_currentStatementChain;
	
	
	private function _closeChain(){
		if($this->_currentStatementChain){
			$this->_statementQueue->enqueue($this->_currentStatementChain);
			$this->_currentStatementChain = null;
		}
	}
	
	public function __construct($environment) {
		$this->_environment = $environment;
		$this->_statementQueue = new \SplQueue;
	}
	
	
	
	public function addFunction() {
		$args = func_get_args();
		
		$body = array_pop($args);
		
		$this->add(new Statement\FunctionStatement($name, $args, $body));
		
	}
	
	public function call(){
		$args = func_get_args();
		$name = array_shift($args);
		$this->add(new Statement\CallStatement($name, $args));
		
	}
	
	public function add(AbstractStatement $statement){
		$this->_closeChain();
		$this->_statementQueue->enqueue($statement);
	}

	public function toJson(){
		$statements = array();
		foreach($this->_statementQueue as $statement){
			$statements[] = array(
					$statement->getName($this->_environment) => $statement->getJsonStructure()
			);
		}
		$output = new \stdClass();
		$output->status = 'success';
		$output->type = 'statement';
		$output->statements = $statements;

		return json_encode($output, JSON_FORCE_OBJECT);
	}
	
}