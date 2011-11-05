/**
 * Handles all of the interactions for the docs pages
 */
window.addEvent('domready', function(){
	Array.each($$('.tabMenu .active'), function(item,index){
		var tabLeftStart = (item.getSize().x/2) - 26; //The width of the tab image
		$$('.tabMenu .active:after').setStyle('left', tabLeftStart);
	});
	var myTips = new Tips('.tips', {text:'rel', 'className':'tipContainer'});
	var mySmoothScroll = new Fx.SmoothScroll({links:'.topLink, .classLink, .scroll', offset:{x:0, y:-80}});
	if ($$('pre').length > 0){
		$$('pre').each(function(el){
			var copy = new Element('span', {
				text:'Copy to clipboard',
				'class':'clipboard'
			});
			var container = new Element('span', {'class':'copyContainer'}).adopt(copy).inject(el, 'before');
			el.light({
				altLines: 'hover',
				indent: 3,
				mode: 'ol',
				clipboard: copy
			});
		});
	}
	if ($$('.tab').length > 0){
		var tabs = new SimpleTabs($$('.tab'), {activeClassName: 'active', triggerEvent: 'click'});
	}	
});