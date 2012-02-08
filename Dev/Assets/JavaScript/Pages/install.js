/**
 * Handles all of the interactions for the install page
 */
window.addEvent('domready', function(){
	new InlineModal('helpLink', 'helpTitle', 'help');
	if ($('environmentFail')){
		new Notification(
			'#environmentFail', 
			'error', 
			'Oops! All listed PHP extensions must be installed and enabled to continue installation.'
		);
	}
});