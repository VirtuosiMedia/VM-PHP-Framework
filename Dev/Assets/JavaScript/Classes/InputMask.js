/**
* @author Virtuosi Media
* @link http://www.virtuosimedia.com
* @version 1.0
* @copyright Copyright (c) 2008, Virtuosi Media
* @license: MIT License
* @description: A function for adding a mask to inputs via their title or label
* Documentation: http://www.virtuosimedia.com
* Requirements: MooTools 1.2 Core - See http://mootools.net
*/
var inputMask = function(inputClass, title){
	if (title != false) { title = true; }
	$$('.'+inputClass).each(function(item){
		var maskText = (title) ? item.get('title') : document.getElement('label[for='+item.get('id')+']').get('html');			
		if (!title) { document.getElement('label[for='+item.get('id')+']').setStyle('display', 'none'); }
		if (item.get('value') == ''){ item.set('value', maskText); }
	});
	
	$$('.'+inputClass).addEvent('focus', function(){													  
		var maskText = (title) ? this.get('title') : document.getElement('label[for='+this.get('id')+']').get('html');
		if (this.get('value') == maskText){ this.set('value', ''); }
	});
	
	$$('.'+inputClass).addEvent('blur', function(){
		var maskText = (title) ? this.get('title') : document.getElement('label[for='+this.get('id')+']').get('html');
		if (this.get('value') == ''){ this.set('value', maskText); }									  
	});		
};