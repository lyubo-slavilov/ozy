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
 * Description of ScriptStatement
 *
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class ScriptStatement extends \Ozy\Statement{
	public function __construct($body, $environment) {
		parent::__construct($environment);
		$bodyProp = $this->_environment == 'dev' ? 'body' : 'b';
		$this->_jsonStructure->{$bodyProp} = $body;
	}
	public function getName() {
		return $this->_environment == 'dev' ? 'script' : 's';
	}
}