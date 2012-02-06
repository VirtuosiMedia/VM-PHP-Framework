/**
 * Handles all of the interactions for the install page
 */
window.addEvent('domready', function(){
	$('help').setStyle('display', 'none');
	$('helpLink').addEvent('click', function(){
		if ($('help').getStyle('display') == 'none'){
			$('help').setStyle('display', 'block');
		} else {
			$('help').setStyle('display', 'none');
		}
	});
});