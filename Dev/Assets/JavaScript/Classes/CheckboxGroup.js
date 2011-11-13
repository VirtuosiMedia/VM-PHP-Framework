/**
* @author Virtuosi Media
* @link http://www.virtuosimedia.com
* @version 1.0
* @copyright Copyright (c) 2011, Virtuosi Media
* @license: MIT License
* @description: Creates a pseudo dropdown select from a checkbox group
* Documentation: http://www.virtuosimedia.com
* Requirements: MooTools 1.4 Core - See http://mootools.net
*/
var CheckboxGroup = new Class({

	Implements: [Events, Options],

	options: {
		activeClass: 'active',
		activeLiClass: 'activeLi',
		checkedLiClass: 'checkedLi',
		containerClass: 'checkboxGroupContainer',
		defaultDisplayText: [], 				//An array of the text to display by default
		displayClass: 'display',
		liClass: 'checkSelectLi',
		listClass: 'checkSelectList',
		multipleDisplayText: [],
		triggerActiveHtml: '&#9650;',
		triggerClass: 'trigger',
		triggerInactiveHtml: '&#9660;',
		singleDisplayText: []
	},

	/**
	 * @param string - inputId - The id of the input to be turned into an inputSelect element
	 * @param array - listOptions - An array of options to appear in the dropdown list
	 * @param object - options - The options object
	 */
	initialize: function(selector, options){
		this.setOptions(options);
		this.selects = $$(selector);
		this.triggers = [];
		this.lists = [];
		this.listOptions = [];
		Array.each(this.selects, function(select, index){
			this.setListOptions(select, index);
			this.createTrigger(select, index);
			this.createList(index);
			this.addNavigation(index);
			select.dispose();
		}.bind(this));
	},
	
	setListOptions: function(select, index){
		var self = this;
		this.listOptions[index] = [];
		Array.each(select.getChildren(), function(child, key){
			this.listOptions[index][key] = child;
		}.bind(this));
		select.setStyle('display', 'none');
	},
	
	createTrigger: function(select, index){
		var self = this;
		var replaceId = (select.get('id')) ? select.get('id')+'Replace' : 'Replace'+index;
		var display = new Element('span', {'class':this.options.displayClass, 'html':this.options.defaultDisplayText[index]});
		var trigger = new Element('span', {'class':this.options.triggerClass, 'html':self.options.triggerInactiveHtml});
		var tabIndex = (select.get('tabindex') >= 0) ? select.get('tabindex') : 0;
		this.triggers[index] = new Element('a', {
			'id':replaceId,
			'class': self.options.containerClass,
			'tabindex':tabIndex,
			events: {
				'click': function(){
					(self.lists[index].getStyle('display') == 'block') ? self.collapseList(index) :	self.expandList(index);
				},
				'focus': function(){
					this.addClass(self.options.activeClass);
				},
				'blur': function(){
					this.removeClass(self.options.activeClass);
				}
			}
		}).adopt(display, trigger).inject(select, 'after');
	},
	
	createList: function(index){
		var self = this;
		
		this.lists[index] = new Element('ul', {
			'class': self.options.listClass,
			styles: {
				'max-height': 300,
				'overflow': 'auto',
				'position': 'absolute',
				'z-index': 1000
			}
		}).inject(this.triggers[index], 'after');
		
		this.createListOptions(index);
		this.lists[index].setStyle('display', 'none');
		this.updateCheckedCount(index);
		
		$(document).addEvent('click', function(e){
			if ((!e.target.hasClass(self.options.triggerClass))&&(!e.target.hasClass(self.options.displayClass))&&(!e.target.hasClass(self.options.listClass))){
				self.collapseList(index);
			}
		});		
	},
	
	createListOptions: function(index){
		Array.each(this.listOptions[index], function(item){
			var option = this.createListItem(index, item);
			this.lists[index].adopt(option);
		}, this);
	},
	
	createListItem: function(index, item){
		var self = this;
		var liClass = (item.getElement('input').checked) ? self.options.checkedLiClass+' '+self.options.liClass : self.options.liClass;
		var liItem = new Element('li', {'class':liClass}).adopt(item);
		liItem.getElement('input').addEvent('change', function(){
			this.getParents('li')[0].toggleClass(self.options.checkedLiClass);
			self.updateCheckedCount(index);
			self.expandList(index);
		});
		return liItem;
	},

	updateCheckedCount: function(index){
		var numChecked = this.lists[index].getElements('.'+this.options.checkedLiClass).length;
		if (numChecked == 0){
			var text = (this.options.defaultDisplayText[index]) ? this.options.defaultDisplayText[index] : '0 Items';
		} else if (numChecked == 1){
			var text = (this.options.singleDisplayText[index]) ? numChecked+' '+this.options.singleDisplayText[index] : '1 Item';
		} else {
			var text = (this.options.multipleDisplayText[index]) ? numChecked+' '+this.options.multipleDisplayText[index] : numChecked+' Items';
		}
		this.triggers[index].getElement('.'+this.options.displayClass).set('html', text);
	},
	
	expandList: function(index){
		var dimensions = this.triggers[index].getCoordinates();
		this.lists[index].setStyles({'display':'block', 'left':dimensions.left, 'min-width':(dimensions.width - 2)});
		var listBottom = dimensions.bottom + this.lists[index].getSize().y;
		var listTop = dimensions.top - this.lists[index].getSize().y;
		if ((listBottom > (window.innerHeight + window.getScroll().y))&&(listTop > window.getScroll().y)){
			this.lists[index].setStyle('top', dimensions.top - this.lists[index].getSize().y).removeClass('down').addClass('up');
			var direction = 'up';
		} else {
			this.lists[index].setStyle('top', dimensions.bottom).removeClass('up').addClass('down');
			var direction = 'down';
		}
		this.triggers[index].getElement('.'+this.options.triggerClass).set('html', this.options.triggerActiveHtml);
		this.triggers[index].addClass(this.options.expandedClass).removeClass('up').removeClass('down').addClass(direction);		
	},
	
	collapseList: function(index){
		this.lists[index].setStyle('display', 'none');
		this.triggers[index].getElement('.'+this.options.triggerClass).set('html', this.options.triggerInactiveHtml);
		this.triggers[index].removeClass(this.options.expandedClass);	
	},
	
	addNavigation: function(index){
		var self = this;
		this.triggers[index].addEvent('keydown', function(e){
			if (e.key != 'tab'){
				e.stop();
			}
			if ((self.lists[index].getStyle('display') == 'none')&&((e.key == 'space')||((['down', 'up'].contains(e.key))&&(e.alt)))){
				self.expandList(index);
			} else if ((e.key == 'esc')||((['down', 'up'].contains(e.key))&&(e.alt))){
				self.collapseList(index);
			}
		});
		this.lists[index].getFirst('li').addEvent('keydown', function(e){
			if ((e.key == 'tab')&&(e.shift)){
				self.collapseList(index);
			}
		});
		this.lists[index].getLast('li').addEvent('keydown', function(e){
			if (e.key == 'tab'){
				self.collapseList(index);
			}
		});		
	}
});