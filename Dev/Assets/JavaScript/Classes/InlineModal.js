/**
* @author Virtuosi Media
* @link http://www.virtuosimedia.com
* @version 1.0
* @copyright Copyright (c) 2012, Virtuosi Media
* @license: MIT License
* @description: Creates a modal window from inline text
* Documentation: http://www.virtuosimedia.com
* Requirements: MooTools 1.4 Core - See http://mootools.net
*/
var InlineModal = new Class({

	Implements: [Events, Options],

	options: {

	},

	/**
	 * @param string - selector - The selector of the selects to be replaced
	 * @param object - options - The options object
	 */
	initialize: function(triggerId, titleId, contentId, options){
		this.setOptions(options);
		$(triggerId).addEvent('click', function(){
			this.open();
		}.bind(this));
		
		this.title = $(titleId);
		this.content = $(contentId);
		this.hideContent();
	},
	
	hideContent: function(){
		this.title.setStyle('display', 'none');
		this.content.setStyle('display', 'none');
	},
	
	createOverlay: function(){
		var displayHeight = window.getSize();
		var overlay = new Element('div', {
			'id': 'overlay',
			styles: {
				height: displayHeight.y
			},
			events: {
				click: function(){
					$('overlay').dispose();
					$('modal').dispose();
				}
			}
		}, this);
		overlay.inject($(document.body));		
	},
	
	createContainer: function(){
		var titleText = this.title.get('html');
		var content = this.content.get('html');
		titleText = (titleText) ? titleText : 'Help';
		content = (content) ? content : 'No help content is available for this page.';
		
		var title = new Element('h3', {html:titleText});
		var titleBar = new Element('div', {'class': 'titleBar'}).adopt(title);
		var contentBox = new Element('div', {html: content, 'class': 'content'});
		var modal = new Element('div', {'id': 'modal'}).adopt(titleBar).adopt(contentBox).inject($(document.body));
	},
	
	open: function(){
		this.createOverlay();
		this.createContainer();
	},
	
	close: function(){
		$('overlay').dispose();
		$('modal').dispose();
	},
	
	toggle: function(){
		($('overlay')) ? this.close() : this.open();
	}
});