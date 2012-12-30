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
use Ozy\Statement\FunctionStatement;
use Ozy\Statement\CallStatement;
use Ozy\Statement\JqueryStatement;
use Ozy\Statement\ScriptStatement;

/**
 * TODO
 *
 * @package ozy
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class Engine {
	
	/**
	 * @var \SplQueue 
	 */
	private $_statementQueue;
	
	private $_environment;
	
	private $_currentStatementChain = null;
	
	/**
	 * Closes any opened statement chain and enqueues it to the queue
	 */
	private function _closeChain(){
		if($this->_currentStatementChain){
			$this->_statementQueue->enqueue($this->_currentStatementChain);
			$this->_currentStatementChain = null;
		}
	}
	
	/**
	 * Constructor - Sets the environment and initializes the statements queue
	 * @param string $environment The environment in which Ozy operates
	 * 
	 */
	public function __construct($environment = 'dev') {
		
		$environment = $environment != 'prod' ? 'dev' : $environment;
		
		$this->_environment = $environment;
		$this->_statementQueue = new \SplQueue;
	}
	
	/**
	 * Adds a FunctionStatement statement
	 * Arguments:
	 *	1st:						the name of the function
	 *	2nd - (n-1)th:	Names of arguments
	 *  nth:						Body of the function
	 * Example:
	 * <code>
	 * <?php
	 *	$ozy = new Ozy\StatementManager();
	 *  $ozy->addFunction('my_calc', 'a', 'b', 'return a + b;')
	 *	/...
	 * ?>
	 * </code>
	 * This will produce JS function which is same as this JS definition:
	 * <code>
	 * function my_calc(a, b){
	 *	return a + b;
	 * }
	 * </code>
	 */
	public function addFunction() {
		$args = func_get_args();
		$name = @array_shift($arg);
		$body = @array_pop($args);
		
		return $this->addStatement(new FunctionStatement($name, $args, $body, $this->_environment));
		
	}
	
	/**
	 * Call a JS function
	 * Arguments:
	 *	1st:				Function to be called
	 *	2nd - nth:	Function parameters
	 * Example:
	 * <code>
	 * <?php
	 *	$ozy = new Ozy\StatementManager();
	 *	$ozy->call('alert', 'Aloha!');
	 *  $ozy->call('my_calc', 6, 8);
	 *	//...
	 * ?>
	 * </code>
	 * This is equivalend to this JS code:
	 * <code>
	 *	alert('Aloha!');
	 *  my_calc(6,8);
	 * </code>
	 */
	public function call(){
		$args = func_get_args();
		$name = @array_shift($args);
		return $this->addStatement(new CallStatement($name, $args, $this->_environment));
	}
	/**
	 * Adds a statement to the queue 
	 * @param \Ozy\Statement\AbstractStatement $statement
	 */
	public function addStatement(Statement $statement){
		$this->_closeChain();
		$this->_statementQueue->enqueue($statement);
		return $this;
	}
	
	public function jquery($selector){
		$this->_closeChain();
		$jquery = new JqueryStatement($selector, $this->_environment);
		$this->_currentStatementChain = $jquery;
		return $this;
	}
	
	public function script($body){
		return $this->addStatement(new ScriptStatement($body, $this->_environment));
	}

	/**
	 * 
	 * @return string A JSON representation of all statements
	 */
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

		return json_encode($output);
	}
	
	public function __call($name, $arguments) {
		if(null !== $this->_currentStatementChain){
			if(is_callable(array($this->_currentStatementChain, $name))){
				call_user_func(array($this->_currentStatementChain, $name), $arguments);
				return $this;
			}else{
				$className = get_class($this->_currentStatementChain);
				throw new Ozy\Exception(sprintf('The current statement in the chain %s has no method %s()',$className, $name));
			}
		}else{
			throw new Ozy\Exception(sprintf('Ozy engine has no method %s()', $name));
		}
	}
}