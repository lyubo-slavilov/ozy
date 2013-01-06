<?php

/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$what = $_POST['method']?$_POST['method']:$_GET['method'];

$source = file_get_contents(__DIR__.'/controller.php');

$tokens = token_get_all($source);

//var_dump($tokens);

$foundFunctions = array();

$current = 0;
while($current < count($tokens)){
	$token = $tokens[$current];
	if(is_array($token)){
		if($token[0] == T_FUNCTION){
			$funct['start'] = $token[2];
			$funct['name'] =  findFunctionName($tokens, $current);
			$end = findFunctionEnd($tokens, $current);
			$funct['end'] = $end[0];
			$current = $end[1];
			
			$foundFunctions[$funct['name']] = $funct;
			$funct = array();
		}
	}
	$current++;
}


$lines = file(__DIR__.'/controller.php');

$targetFunct = @$foundFunctions[$what.'Action'];

if($targetFunct){
	
	$src = 
	"<?php
//controller.php	
class DemoController
{
	//...

";
	for ($i = $targetFunct['start']; $i <= $targetFunct['end']; $i++) {
		$src .= $lines[$i-1];
	}
	$src .=
	'
	//...
}
?>';
	
	$src = str_replace('$ozy = $this->createOzy();', '$ozy = new \Ozy\Engine();', $src);
	
	$phpHtml = '<div class="code"><h3>Server side</h3>';
	$phpHtml .= highlight_string($src, true);
	$phpHtml .= '</div>';
	
	
	$htmlHtml = $_POST['html']?$_POST['html']:$_GET['html'];
	$htmlHtml = str_replace('class="caller"', '', $htmlHtml);
	$htmlHtml .= "\n\nOr\n\n<script>\n\n\t".str_replace('return false;', '', @$_POST['js'])."\n\n</script>";
	$htmlHtml =  nl2br(str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', htmlentities($htmlHtml)));
}else{
	$phpHtml = "Method $what not found!";
	$htmlHtml = '';
}

require __DIR__.'/../lib/Ozy/Autoloader.php';
\Ozy\Autoloader::register();


$ozy = new Ozy\Engine();

$ozy
	->jquery("[ozy-call=$what] div.source div.html")
		->html('<h3>Client side</h3><code>'.$htmlHtml.'</code>')
		->css(array('color' => 'green',  'fontFamily'=> 'Lucida Console', 'font-size'))
		->fadeIn()
	->jquery("[ozy-call=$what] div.source div.php")
		->html($phpHtml)
		->fadeIn();

header('Content-Type: application/json');
echo $ozy->toJson();


//FUNCTIONS

function findFunctionName($tokens, $start){
	$tokenFound = false;
	$current = $start;
	while(!$tokenFound && $current < count($tokens)){
		if($tokens[$current][0] == T_STRING) return $tokens[$current][1];
		$current++;
	}
}

function findFunctionEnd($tokens, $start){
	$opened = 0;
	$lastLine = 0;
	$firstfound = false;
	for($i = $start; $i < count($tokens); $i++){
		$token = $tokens[$i];
		if(@$token[2]) $lastLine = $token[2];
		if(@$token[0] == T_WHITESPACE){
			$lastLine += substr_count($token[1], "\n");
		}
		if($token == '{'){
			$opened++;
			$firstfound = true;
		}
		if($token == '}'){
			$opened--;
		}
		
		if($firstfound && $opened == 0){
			return array($lastLine, $i);
		}
	}
	return array($lastLine, $start);
}
?>
