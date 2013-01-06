<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ozy\Symfony;

use Symfony\Component\HttpFoundation\Response as SfResponse;

/**
 * Description of Response
 *
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class Response extends SfResponse {

	static public $ozy;

	public function __construct($kernel) {
		parent::__construct();

		if (!self::$ozy) {
			self::$ozy = new \Ozy\Engine($kernel->getEnvironment());
		}
	}

	public function send() {
		$this->headers->set('Content-Type', 'application/json');
		$this->setContent(self::$ozy->toJson());
		return parent::send();
	}

	public function __call($method, $arguments) {
		return call_user_func(array(self::$ozy, $method), $arguments);
	}

}