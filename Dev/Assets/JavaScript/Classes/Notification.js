/**
* @author Virtuosi Media
* @link http://www.virtuosimedia.com
* @version 1.0
* @copyright Copyright (c) 2012, Virtuosi Media
* @license: MIT License
* @description: Creates a notification bar at the top of the page
* Documentation: http://www.virtuosimedia.com
* Requirements: MooTools 1.4 Core - See http://mootools.net
*/
var Notification = new Class({

	Implements: [Events, Options],

	/**
	 * @param string - trigger - The selector of the selects to be replaced
	 * @param string - notificationClass - The class of notification
	 * @param string - content - The content of the notification
	 */
	initialize: function(trigger, notificationClass, content){
		$$(trigger).addEvent('click', function(e){
			e.stop();
			this.createNotification();
		}.bind(this));
		
		this.notificationClass = notificationClass;
		this.content = content;
	},
	
	hideContent: function(){
		this.title.setStyle('display', 'none');
		this.content.setStyle('display', 'none');
	},
	
	createNotification: function(){
		var notification = new Element('div', {
			'class': 'notification '+this.notificationClass,
		}, this);
		
		var notificationContent = new Element('div', {
			'class': 'notificationContent',
			'html':this.content
		}, this).inject(notification);;
		
		var notificationClose = new Element('div', {
			'class': 'notificationClose',
			'html':'x',
			events: {
				click: function(){
					this.getParent('.notification').dispose();
				}
			}
		}).inject(notification);		
		notification.inject($(document.body), 'top');		
	}
});