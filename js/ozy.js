/* 
 * This file is part of the Ozy package.
 * 
 * (c) Lyubomir Slavilov <lyubo.slavilov@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


var ozy = {};
(function($){
	ozy = {
		call: function(){
			//Handle paramters
			var url = arguments[0] || '';
			var parameters = arguments[1] || '';
			var callback = null;
			var type = arguments[3] || arguments[2] || 'json';
			if(typeof type == 'function'){
				callback = type;
				type = 'json'
			}else{
				if(typeof arguments[2] == 'function'){
					callback = arguments[2];
				}
			}
			
			//console.log({url: url, parameters: parameters, callback: callback, type: type});
			
			$.post(
				url,
				parameters,
				function(data){
					if(type == 'json'){
						
						ozy.fn.execCustomCallback(callback, data, 'before');
						ozy.fn.handleOzyResponse(data);
						ozy.fn.execCustomCallback(callback, data, 'after');
					}else{
						ozy.fn.execCustomCallback(callback, data, 'none');
					}
				},
				type
			).error(function(a,b,c){
				console.log('Error on ozy ajax response');
			})	
		},
		fn: {
			halt: function(message){
				console.log('[Ozy halt]: '+message);
			},
			validateOzyResponse: function(data){
				if(
						typeof data.status == undefined ||
						typeof data.type == undefined
				){
						return {valid: false, message: 'Response does not seems as valid Ozy Response'}
				}
				if('|success|error|'.indexOf('|'+data.status+'|') < 0){
					return {valid: false, message: 'Invalid Ozy response status: '+ data.status}
				}
				if('|statement-chain|'.indexOf('|'+data.type+'|') < 0){
					return {valid: false, message: 'Invalid Ozy response type: '+ data.type}
				}
				return {valid: true};
			},
			execCustomCallback: function(callback, data, position){
				if(typeof callback == 'function'){
					callback(data, position);
				}
			},
			handleOzyResponse: function(data){
				
				var validationResult = ozy.fn.validateOzyResponse(data);
				
				if(!validationResult.valid){
					ozy.fn.halt(validationResult.message);
				}
				if(data.status == 'success'){
					
					for(var s in data.statements){
						var statement = data.statements[s];
						
						var handler = 'handle'+ozy.fn.ucfirst(statement.n);
						
						if(typeof ozy.statement[handler] == 'function'){
							
							ozy.statement[handler].apply(ozy.statement, [statement.o]);
						}else{
							//TODO log this more clever
							console.log('Unknown statement handler: '+ handler);
						}
					}
				}else{
					ozy.fn.halt('Server respond with Ozy error: '+data.message);
				}
			},
			ucfirst: function(str){
				str += '';
				var f = str.charAt(0).toUpperCase();
				return f + str.substr(1);
			}
		},
		statement:{
			
			handleCall: function(object){
				
				var name = object.name || object.n;
				
				var params = object.parameters || object.p;
				if($.browser.msie && $.browser.version < 9){
					//Of cource! You can't use .apply() on IE < 9 native shitz such az alert(), open(), etc...!
					//What a surprize, uh?
					//So we have to do this stupid:
					var p = '"'+params.join('","')+'"';
					eval(name+'('+p+')');
				}else{
					//Here is the truth!
					if(typeof window[name] == 'function'){
						window[name].apply(window, params);
					}
				}
			},
			handleC: function(object){ return this.handleCall(object)},
			
			handleScript: function(object){
				var body = object.body || object.b;
				var html = '<'+'scr'+'ipt'+'>';
				html += body;
				html += '<'+'/'+'scr'+'ipt'+'>'
				$('head').append($(html));
			},
			handleS: function(object){return this.handleScript(object)},
			
			handleFunction: function(object){
				var name = object.name || object.n;
				var args = object.arguments || object.a;
				var body = object.body || object.b;
				window[name] = new Function(args, body);
			},
			handleF: function(object){return this.handleFunction(object)},
			
			handleJquery: function(object){
					var selector = object.selector || object.s;
					var chain = object.chain || object.c;
					var $jqObject = $(selector);
					
					var m, p;
					var chainCopy = chain.slice(0);
					for(var c in chain){
						chainCopy.splice(c, 1);
						m = chain[c].method || chain[c].m;
						p = chain[c].parameters || chain[c].p;
						if(m == 'each'){
							if(p.length == 1){
								return $jqObject.each(new Function('i', 'el', p[0]));
							}else{
								return ozy.statement._handleJqueryEachChain($jqObject, chainCopy);
							}
						}
						$jqObject = $jqObject[m].apply($jqObject, p);
					}
					return $jqObject;
			},
			handleJ: function(object){return this.handleJquery(object)},
			
			_handleJqueryEachChain: function($jqObject, _chain){
				var _chainCopy = _chain.slice(0);
				$jqObject.each(function(i, el){
					var $jq = $(el);
					var m, p;
					
					for(var c in _chain){
						_chainCopy.splice(c, 1);												
						m = _chain[c].method || _chain[c].m;
						p = _chain[c].parameters || _chain[c].p;
						if(m == 'each'){
							//skeep .each()
							continue;
							//TODO handle this somehow
							
						}
						$jq = $jq[m].apply($jq, p);
					}
				});
			}
			
		}
	}
})(jQuery);

