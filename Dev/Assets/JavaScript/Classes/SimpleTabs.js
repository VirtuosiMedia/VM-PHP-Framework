/**
* @author Virtuosi Media
* @link http://www.virtuosimedia.com
* @version 1.0
* @copyright Copyright (c) 2010, Virtuosi Media
* @License: MIT License
* Description: A simple tabs class
* Documentation: http://www.virtuosimedia.com
* Requirements: MooTools 1.2 Core - See http://mootools.net
*/
var SimpleTabs = new Class({

	Implements: [Events, Options],

	options: {
		activeClass: 'active',
		startTabNumber: 1,
		triggerEvent: 'click',
		startClosed: false
	},

	initialize: function(tabs, options){
		this.setOptions(options);
		this.tabs = tabs;
		
		var self = this;
		this.tabs.addEvent(this.options.triggerEvent, function(e){
			var hash = this.get('href').split('#')[1];
			self.setTabs(this, hash);
			self.setHash(hash);
			return false;
		});

		this.initializeTabs();
		this.currentHash = window.location.hash;
		var changeHash = this.checkHash.periodical(120, this);
	},

	initializeTabs: function(){
		this.tabs.each(function(item){
			var hash = item.get('href').split('#')[1];
			if (this.options.startClosed){
				$(hash).setStyles({'display':'none', 'height':'0px'});				
			} else if ((item.hasClass(this.options.activeClass))||(window.location.hash.substring(1) == hash)) {
				this.setTabs(item, hash);
			} 
		}, this);		
	},
	
	setTabs: function(currentTab, hash){
		this.tabs.removeClass(this.options.activeClass).each(function(item){
			$(item.get('href').split('#')[1]).setStyles({'display':'none', 'height':'0px'});
		});
		$(hash).setStyles({'display':'block', 'height':'100%'});
		currentTab.addClass(this.options.activeClass);		
	},
	
	setHash: function(hash){
		$(hash).set('id', hash+'Temp');
		window.location.hash = '#'+hash;		
		$(hash+'Temp').set('id', hash);
	},
	
	checkHash: function(){
		if (window.location.hash != this.currentHash){
			this.initializeTabs();
			this.currentHash = window.location.hash;
		}
	}
});