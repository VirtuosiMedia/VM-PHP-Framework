/**
 * Handles all of the interactions for the tools page
 */
window.addEvent('domready', function(){
	var tabs = new SimpleTabs($$('.tab'), {activeClassName: 'active', triggerEvent: 'click'});
});