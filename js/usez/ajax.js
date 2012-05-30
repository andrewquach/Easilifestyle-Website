/*  Prototype JavaScript framework, version 1.5.0_rc1
 *  (c) 2005 Sam Stephenson <sam@conio.net>
 *
 *  Prototype is freely distributable under the terms of an MIT-style license.
 *  For details, see the Prototype web site: http://prototype.conio.net/
 *
/*--------------------------------------------------------------------------*/
function ajax_link(update_holder, url, loading_holder, loading_string, loaded_holder, loaded_string){
	if(loaded_holder && loaded_holder!=""){
		new Ajax.Updater(update_holder,url, 
			{	asynchronous:true, 
				evalScripts:true, 
				onLoading:function(request) {
					$(loading_holder).innerHTML = loading_string;
				},
				onComplete:function(request) {
					$(loaded_holder).innerHTML = loaded_string;
				}
			}
		);
	}else{
		new Ajax.Updater(update_holder,url, 
			{	asynchronous:true, 
				evalScripts:true, 
				onLoading:function(request) {
					$(loading_holder).innerHTML = loading_string;
				}
			}
		);
	}
}

function ajax_submit(update_holder, url, loading_holder, loading_string, form, loaded_holder, loaded_string){
	if(loaded_holder && loaded_holder!=""){
		new Ajax.Updater(update_holder,url, 
			{	asynchronous:true, 
				evalScripts:true, 
				onLoading:function(request) {
					$(loading_holder).innerHTML = loading_string;
				},
				onComplete:function(request) {
					$(loaded_holder).innerHTML = loaded_string;
				},
				parameters:Form.serialize($(form)), 
		 		requestHeaders:['X-Update', update_holder]
			}
		);
	}else{
		new Ajax.Updater(update_holder,url, 
			{	asynchronous:true, 
				evalScripts:true, 
				onLoading:function(request) {
					$(loading_holder).innerHTML = loading_string;
				},
				parameters:Form.serialize($(form)), 
		 		requestHeaders:['X-Update', update_holder]
			}
		);
	}
}
