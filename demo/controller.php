<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../lib/Autoloader.php';
Ozy\Autoloader::register();

class DemoController
{
	private $env = 'dev';
	
	public function indexAction(){
		include 'index.php';
		die();
	}
	/**
	 * @invoker
	 * io.call();
	 */
	public function helloAction(){
		$ozy = $this->createOzy();
		
		$ozy->call('alert', 'Welcome to Ozy demo!');
		return $ozy;
	}
	
	public function executeScriptAction(){
		$ozy = $this->createOzy();
		
		$ozy->script("var foo='baz'; alert('Your bar is the new '+foo);");
		return $ozy;
	}
	
	public function functionSumAction(){
		$ozy = $this->createOzy();
		
		$ozy
			//the important part
			->addFunction('sum', 'a', 'b',  'var c=a+b; alert("The answer is: "+c)' )
			//demo-purpose part
			->jquery('.sum-function-status')
						->text('ok')
						->css(array(
								'color'=>'green', 
								'fontWeight'=>'bold',
								'textShadow'=>'0 1px white'
						))
						->show()
						->fadeOut(1000);
		return $ozy;
	}
	
	public function jquerySimpleAction(){
		$ozy = $this->createOzy();
		
		$ozy->jquery('#boring-rectangle')->width(150);
		$ozy->jquery('#boring-rectangle')->text('Pritty rectangle');
		$ozy->jquery('#boring-rectangle')->css(array('background' => '#EFFAFA'));
		
		return $ozy;
	}
	
	public function jqueryChainingAction(){
		$ozy = $this->createOzy();
		
		$ozy->jquery('#boring-rectangle2')
			->width(150)
			->text('Gorgeous rectangle')
			->css(array(
					'background' => '#EFFAFA',
					'textShadow' => '0 1px white',
					'borderRadius' => '4px',
					'boxShadow' => '0 0 20px rgba(0,0,0,.4), 0 60px 40px -40px white inset'
			));
		
		return $ozy;
	}
	
	public function jqueryEachAction(){
		$ozy = $this->createOzy();
		/**
		 * Code below is equivalent to this JS code:
		 * 
		 * $('#list-of-truths li).each(function(i, el){
		 *   $(el).css({color: 'lightgreen'})
		 * });
		 * 
		 */
		$ozy->jquery('#list-of-truths li')->each('
			$(el).css({color: "lightgreen"});
		');
		return $ozy;
	}
	
	public function jqueryEachChainAction(){
		$ozy = $this->createOzy();
		/**
		 * Code below is equivalent to this JS code:
		 * 
		 * $('#list-of-truths li).each(function(){
		 *   $(this).css({color: 'silver'})
		 *          .find('span')
		 *          .css({color: 'red'});
		 * });
		 * 
		 */
		$ozy->jquery('#list-of-truths2 li')->each()
					->css(array('color' => 'silver'))
					->find('span')
					->css(array('color' => 'red'));
		
		return $ozy;
	}
	
	public function testAction(){
		$ozy = $this->createOzy('dev');
		
		$ozy->jquery('#list-of-truths li')->each()
					->css(array('color' => 'silver'))
					->find('span')
					->css(array('color' => 'red'));
						//->text('banana');
		
		
		return $ozy;
	}
	//Some helper methods
	
	public function __construct($env='prod') {
		$this->env = $env;
	}
	private function createOzy($env = null){
		return new Ozy\Engine($env != null?$env:$this->env);
	}
	
}




/**
 * Dispatch the request
 */

$controller = new DemoController();
$path = $_SERVER['PATH_INFO'];

if($path == '/') 
	$action = 'index';
else{
	$action = str_replace('/', '', $path);
}
$action .= 'Action';

if(is_callable(array($controller, $action))){
	$ozy = call_user_func(array($controller, $action));
	
}else{
	$ozy = new Ozy\Engine();
	$ozy->call('alert', 'Invalid action: '.$action);
}
header('Content-Type: application/json');
echo $ozy->toJson();
	


//require __DIR__ . '/../lib/Autoloader.php';
//Ozy\Autoloader::register();
//
//$ozy = new Ozy\Engine();
//
//$ozy
//	->call('alert', 'Aloha')
//	->addFunction('foo', 'bar', 'baz', 'return foo + bar + baz;')
//	->jquery('body')
//		->show()
//		->css(array(
//				'background' => 'none',
//				'color' => 'black'
//		))
//		->fadeIn()
//	->call('allSettedUp')
//	->script('vas a = \'aloha!\'');
//
//header('Content-Type: application/json');
//echo $ozy->toJson();

