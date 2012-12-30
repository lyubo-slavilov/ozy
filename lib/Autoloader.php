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

/**
 * Ozy Autoloader
 *
 * @author Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 */
class Autoloader {

	/**
	 * Registers Ozy\Autoloader as an SPL autoloader.
	 */
	public static function register() {
		ini_set('unserialize_callback_func', 'spl_autoload_call');
		spl_autoload_register(array(new self, 'autoload'));
	}

	/**
	 * Handles autoloading of Ozy classes.
	 *
	 * @param string $class A class name.
	 */
	public static function autoload($class) {
		
		if (0 !== strpos($class, 'Ozy')) {
			return;
		}
		$file = str_replace('Ozy\\', '', $class);
		$file = dirname(__FILE__) . '/' . str_replace(array('\\', "\0"), array('/', ''), $file) . '.php';
		//die($file);
		if (is_file($file)) {
			require $file;
		}
	}

}