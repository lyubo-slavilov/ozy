<?php
/*
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if(@$_GET['ozyjs']){
	header('Content-Type: text/javascript');
	die(file_get_contents(__DIR__.'/../js/ozy.js'));
}

?>
<html>
  <head>
    <title>Ozy Demo Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Text+Me+One' rel='stylesheet' type='text/css'>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="?ozyjs=1"></script>
		<script>
			$(function(){
				
				$('.note').prepend('<div class="note-image"></div>');
				
				$('div[ozy-call]').each(function(){
					var $p = $(this);
					
					var $h = $p.prev('h1');
					var a = $p.attr('ozy-call')+'_anchor';
					$h.attr('id', a);
					
					var $ul = $('ul.menu');
					$ul.append($('<li><a href="#'+a+'">'+$h.text()+'</a></li>'));
					
					if($p.is('.nosource')) return;
					
					$p.find('a.caller').attr('onclick', 'ozy.call(\'./'+$(this).attr('ozy-call')+'/\'); return false;');
					$p.append($('<div class="source"><a class="reveal-source" href="#">View Source</a><div class="html"></div><div class="php"></div></div>'));
					$p.find('a.reveal-source').click(function(){
						
						if($(this).is('.opened')){
							$p.find('div.html').fadeOut();
							$p.find('div.php').fadeOut();
							$(this).text('View Source');
						}else{
							$(this).text('Hide Source');
							ozy.call('revealsrc.php', {
								method: $p.attr('ozy-call'), 
								html: $p.find('a.caller').clone().wrap('<div>').parent().html(),
								js: $p.find('a.caller').attr('onclick')
							});
						}
						$(this).toggleClass('opened');
						return false;
					});
					
					
				});
				
			});
		</script>
		<style>
			body{
				 font-family: 'Trebuchet-MS', sans-serif;
				 color: #424242;
				 font-size: 14px;
			}
			h1, h2, h3{
				font-family: 'Monda', sans-serif;
			}
			h1{font-size: 1.6em}
			h2{font-size: 1.4em}
			h3,h4{font-size: 1.2em;}
			div.wrapper{
				width: 900px;
				margin: 0 auto;
				padding: 30px;
				background: #FdFdFd;
				border-radius: 8px 8px 0 0;
				box-shadow: 0 2px 10px gray;
			}
			div.left{
				width: 200px;
				float: left;
				margin-right: 20px;
			}
			div.right{
				width: 680px;
				float: left;
			}
			div.right h1:first-child, div.left h1:first-child{
				margin-top: 0;
			}
			div.html, div.php{
				padding: 10px 10px;
				background: whitesmoke;
				border: 1px dashed silver;
				display: none;
				margin-bottom: 20px;
			}
			
			div.source{
				margin-top: 10px;
				border-top: 1px solid whitesmoke;
				border-bottom: 2px solid silver;
			}
			div.source h3{
				margin: -5px 0 10px 0;
				color: #858585;
			}
			code{
				font-size: 11px;
			}
			code.i{
				background: #EFFAFA;
				font-size: 14px;
				color: green;
			}
			div.actions{
				background: #f0f0f0;
				padding: 10px;
				margin-top: 5px;
				
			}
			
			a{
				font-size: 0.9em;
				color: #49A6DA;
				text-decoration: none;
				text-shadow: 0 1px rgba(255,255,255,.7);
			}
			a:hover{
				text-decoration: underline;
			}
			a.reveal-source{
				float: right;
				margin-top: -38px;
				margin-right: 10px;
			}
			ul.menu{
				padding: 0;
				margin: 0;
			}
			ul.menu li{
				list-style: none;
				padding: 10px 5px;
				border-bottom: 1px solid whitesmoke;
			}
			div.note{
				margin: 5px 10px;
				border-radius: 0 27px 0 0;
				background: whiteSmoke;
				position: relative;
				padding: 30px 45px 30px;
			}
			div.note .note-image{
				background: url(./images/note.png);
				width: 42px;
				height: 42px;
				position: absolute;
				right: 5px;
				top: 5px;
			}
		</style>
  </head>
  <body>
		<div class="wrapper">
			<div class="left">
				<h1>Menu</h1>
				<ul class="menu"></ul>
			</div>
			<div class="right">
				<span style="float: right">
					<a href="#" onclick="$('.reveal-source').removeClass('opened').click(); return false;">View all</a> | 
					<a href="#"  onclick="$('.reveal-source').addClass('opened').click(); return false;">Hide all</a>
				</span>
				<h1>Introduction</h1>
				<div ozy-call="intro" class="nosource">
					<p>
						Ozy is a PHP and Javascript tool which gives you the ability to execute client side Javascript code from your server side PHP application.
					</p>
					<p>
						Ozy is inspired from libraries like XAJAX and Phery but it is written in mind that in modern frameworks it should operates outside custom PHP functions. In theory, you can use Ozy in any php file, front controller, controller action or whatever.
					</p>
					<p>
						If you use PHP framework as Symfony or Zend you can implement Ozy as a response class in order to fit it in the framework super natively.
					</p>
					<h3>About this demo</h3>
					<p>
						This demo page shows basic Ozy usage. On the server side we have simple frontend controller <code class="i">controller.php</code> which handles all AJAX calls. It has public action methods, which handle all specific requests to the controller.
					</p>
					<p>
						All source listings are auto generated and show examples how this demo uses Ozy. They include the client-side part with HTML/JS example how to invoke call to the server and also the server-side part which shows the actual controller's action which processes the request.
					</p>
						
				</div>
				
				<h1>Calling JS function</h1>
				<div ozy-call="hello">
					<p>
						Often when the AJAX execution is on the server side we need to invoke a JS function in order to change something on the client side.<br/>
						Typically we do this with special AJAX response handlers or by injecting javascript code into the response. But these does not work in every case or it is related with more annoying code writing.<br/>
					</p>
					<p>
						With <code class="i">Ozy</code> this is simple. Just do <code class="i">$ozy->call('myFunction', 'foo', 'bar', 'baz', ...);</code> and <code class="i">Ozy</code> will do its magic for you.
					</p>
					<p>
						Click the link bellow to see how it works.
					</p>
					<div class="actions">
						<a href="#" class="caller">Call alert() from server</a>
					</div>
				</div>

				<h1>Executing native JS code</h1>
				<div ozy-call="executeScript">
					<p>
						Like the previous case, we often need to execute some additional JS code on the fly. You can achieve this with simple <code class="i">$ozy->script(' // your js code here.. ');</code> call.
					</p>
					<p>
						Click the link below to see how the server will execute this <code class="i">var foo='baz'; alert('Your bar is the new '+foo);</code> JS code.
					</p>
					<div class="actions">
						<a href="#" class="caller">Execute the code</a>
					</div>
				</div>

				<h1>Register new JS function</h1>
				<div ozy-call="functionSum">
					<p>
					This example demonstrates the verry common situation when you need some JS code to be wrapped in a function, but we will ned this function only in ver specific case.<br/>
					Usually we write our function in one of our JS files, but the code will be loaded every time in every page.
					</p>
					<p>
						Ozy lets you to register such a function in the moment you need it. It is done by <code class="i">$ozy->addFunction()</code> call.
					</p>
					<p>
						Bellow we have two calls of JS function <code class="i">sum()</code>. Keep in mind that the function is not registered to the DOM initially. You have to invoke its registration first before using it.
					</p>
					<p>
						Try to call the function before it will be registered and examine your console.<br/>
						After that click "Register function sum(a,b)" and try again.
					</p>
					<div class="actions">
						<a href="javascript:sum(5,6);">sum(5,6)</a> | 
						<a href="javascript:sum(8,13);">sum(8,13)</a> | 
						<a href="#" class="caller">Register function sum(a,b)</a> <span class="sum-function-status"></span>
					</div>
				</div>
				<h1>jQuery simple example</h1>
				<div ozy-call="jquerySimple">
					<p>
						This example shows how Ozy can handle jQuery manipulations.<br/>
						Imagine we have this pretty boring rectangle:
					</p>
					<div id="boring-rectangle" style="width: 200px; height:100px; line-height: 100px; text-align: center; border: 1px solid gray">Boring rectangle</div>
					<p>
						During the server-side execution we can decide to pritify it little bit by chainging its dimmensions and background color.<br/>
						We can use <code class="i">$ozy->jquery()</code> to obtain a jQuery object which we can chain as usual.
					</p>
					<p>
						Click the link bellow to see how it works.
					</p>
					<div class="actions">
						<a href="#" class="caller">Make it pretty</a>
					</div>
				</div>
				<h1>jQuery chaining</h1>
				<div ozy-call="jqueryChaining">
					<p>
						Well, lets face it! One of the powerfull features of jQuery is its chaining ability. Ozy will be really dull if it can't reproduce that.<br/>
						Now you can see how you can chain Ozy's jQuery calls in order to "do more, write less..."
					</p>
					<p>
						Here are our boring rectangle again:
					</p>
					<div id="boring-rectangle2" style="width: 200px; height:100px; line-height: 100px; text-align: center; border: 1px solid gray">Boring rectangle</div>
					<p>
						Now we will try to make it much better than the first time and on the top we will use method chaining!
					</p>
					<div class="note">
						You will always want to use jQuery chaining, because it produces less JSON output.
					</div>
					<p>
						Click the link bellow to see how it works.
					</p>
					<div class="actions">
						<a href="#" class="caller">Make it gorgeous</a>
					</div>
				</div>
				<h1>jQuery .each()</h1>
				<div ozy-call="jqueryEach">
					<p>
						You can use <code class="i">$ozy->jquery()->each()</code> to loop over a collection of elements, by passing <code class="i">string</code> which will contain the logic for each loop execution.<br/>
					</p>
					<p>
						So, let we have this list:
					</p>
					<ul id="list-of-truths">
						<li><span>Physics</span> drives my crazy and it <span>is</span> not <span>cool</span> at all</li>
						<li>Clever</li>
						<li>Dummy</li>
						<li>Smart</li>
						<li>Funny</li>
					</ul>
					<p>
						We want to change the color of each &gt;li&lt; element.
					</p>
					<p>
						Click the link to see what will happen, and don't forget to check the source!
					</p>
					<div class="actions">
						<a href="#" class="caller">Colorize 'em</a>
					</div>
				</div>
				<h1>jQuery .each() chain</h1>
				<div ozy-call="jqueryEachChain">
					<p>
						This is tricky.<br/>
						You can use <code class="i">$ozy->jquery()->each()->...</code> to invoke specific methods for each jQuery elements in the .each() collection, but you have keep in mind that this functionality is limited at this point.
					</p>
					
					<p>
						As example if you use nasted <code class="i">each()</code> methods in the chain <code class="i">$ozy->jquery('selector')->each()->...->each()->...;</code> this will execute only the first <code class="i">each()</code> and will always ignore the others.
					</p>
					<p>
						So, let's look to our list again:
					</p>
					<ul id="list-of-truths2">
						<li><span>Physics</span> drives my crazy and it <span>is</span> not <span>cool</span> at all</li>
						<li>Clever</li>
						<li>Dummy</li>
						<li>Smart</li>
						<li>Funny</li>
					</ul>
					<div class="note">
						Notice that <code class="i">$ozy->jquery()->each()</code> is called <u>without</u> passing any arguments to <code class="i">each()</code> method.<br/>
						This will give you the ability to change the jQuery context to every element in the collection instead the original selector.
					</div>
					<p>
						Click the link to see how <code class="i">each()</code> works
					</p>
					<div class="actions">
						<a href="#" class="caller">Reveal the truth</a>
					</div>
				</div>
			</div><!-- Right-->
			<br style="clear:both" />
		</div>
  </body>
</html>

