<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../lib/Autoloader.php';
Ozy\Autoloader::register();

$ozy = new Ozy\Engine('prod');

$ozy
	->call('alert', 'Aloha')
	->addFunction('foo', 'bar', 'baz', 'return foo + bar + baz;')
	->jquery('body')
		->show()
		->css(array(
				'background' => 'none',
				'color' => 'black'
		))
		->fadeIn()
	->call('allSettedUp')
	->script('vas a = \'aloha!\'');

header('Content-Type: application/json');
echo $ozy->toJson();
?>
