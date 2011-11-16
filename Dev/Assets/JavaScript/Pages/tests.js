/**
 * Handles all of the interactions for the unit test suite
 */
window.addEvent('domready', function(){
	var myTips = new Tips('.tips', {text:'rel', 'className':'tipContainer'});
	var mySmoothScroll = new Fx.SmoothScroll({links:'.topLink, .classLink', offset:{'x':0, 'y':-80}});
	
	//Form UI
	inputMask('mask', false);
	
	$('saveResultsName').setStyle('display', 'none');
	$$('#saveResults, #saveResultsLabel').addEvent('change', function(){
		if (($('saveResults').checked)&&($('reports').getSelected()[0].get('value') == 'new')){
			$('saveResultsName').setStyle('display', 'block').focus();
			$('saveResultsLabel').set('text', 'save results as');
		} else {
			$('saveResultsName').setStyle('display', 'none');
			$('saveResultsLabel').set('text', 'save results');
		}
	});
	
	var myMultiSelect = [];
	$$('.multiSelect').each(function(item, index){
		var selectTitle = item.getElement('h3').get('text').toLowerCase();
		item.getElement('h3').dispose();
		var options = {
			defaultDisplayText:['all '+selectTitle],
			singleDisplayText:[selectTitle.substring(0, selectTitle.length-1)],
			listId:item.get('id')+'List',
			multipleDisplayText:[selectTitle],
			triggerActiveHtml:'',
			triggerInactiveHtml:''
		};
		
		myMultiSelect[index] = new CheckboxGroup('#'+item.get('id'), options); 
	});
	
	var replaceSelect = new FormReplaceSelect('select', {triggerActiveHtml:'', triggerInactiveHtml:''});
	
	$('reports').addEvent('change', function(){
		if (this.getSelected()[0].get('value') == 'new'){
			$$('.newReport').setStyle('display', 'block');
			$('launch').setStyles({'clear':'none', 'margin-left':'8px'});
			
			if ($('saveResults').checked){
				$('saveResultsName').setStyle('display', 'block');
				$('saveResultsLabel').set('text', 'save results as');
			}			
		} else {
			$$('.newReport').setStyle('display', 'none');
			$('launch').setStyles({'clear':'left', 'margin-left':'0px'});
		}
	});

	if ($('reports').getSelected()[0].get('value') != 'new'){
		$$('.newReport').setStyle('display', 'none');
		$('launch').setStyles({'clear':'left', 'margin-left':'0px'});
	}	
	
	$('launch').addClass('launchButton');

	
	//Form Filtering
	var displayItem = function(item, currentListId, clickedItem){
		var lists = ['groupsList', 'subgroupsList', 'authorsList'];
		var clickedItemId = clickedItem.get('id');
		var itemParentId = item.getParent('ul').get('id');
		var itemFilters = item.get('rel').split(' ');
		var itemVisible = (item.getParent('li').getStyle('display') != 'none') ? true : false;
		var filters = [];
		var display = [];

		//Return early if it's the clicked item and it's checked
		if ((clickedItemId == item.getElement('a').get('id').replace('Replace', ''))&&(clickedItem.checked)){
			return true;
		}
		
		//Populate the filters for each list
		Array.each(lists, function(list, index){
			filters[index] = [];
			display[index] = true;
			$$('#'+list+' a').each(function(checkbox){
				var checkboxId = checkbox.get('id').replace('Replace', '');
				var clickedChecked = ((checkboxId == clickedItemId)&&(clickedItem.checked)) ? true : false; 
				if (((checkbox.hasClass('checked'))&&(checkboxId != clickedItemId))||(clickedChecked)){
					filters[index].push(checkboxId);
				}
			});
		});
		
		//Return early if all there are no checked filters
		if (filters.flatten().length == 0){
			return true;
		}
		
		//Make sure the item matches the necessary filters
		Array.each(lists, function(list, index){
			if (itemParentId != list){
				if ((itemVisible)&&((filters[index].length == 0)||(currentListId == itemParentId)||(item.getElement('a').hasClass('checked')))){
					display[index] = true;
				} else if (filters[index].length == 0){
					display[index] = true;
				} else {
					display[index] = filters[index].some(function(filter){
						return itemFilters.contains(filter);
					});			
				}
			} else {
				display[index] = true;
			}
		});
				
		return (display.contains(false)) ? false : true;
	};
	
	//Update the available filters when a filter is clicked 
	$$('.checkSelectList input').addEvent('change', function(e){
		var currentListId = this.getParent('ul').get('id');
		var allUnchecked = (this.checked) ? false : true; //Inverted because the event is fired before the change
				
		$$('.checkSelectList a').each(function(checkbox){
			allUnchecked = (checkbox.hasClass('checked')) ? false : allUnchecked;
		});
				
		$$('.checkSelectList li .checkContainer').each(function(listItem){
			if ((allUnchecked)||(displayItem(listItem, currentListId, this))){
				listItem.getParent('li').setStyle('display', 'block');
			} else {
				listItem.getParent('li').setStyle('display', 'none');
			}
		}, this);
	});

	var replace = new CheckboxReplace('input[type="checkbox"]', {cloneClasses:true, activeClass:'activeCheck'});
	
	//Set up the UI for each individual test
	$$('.tabMenu').each(function(item){
		var testedClassId = item.get('id');		
		var tabs = new SimpleTabs($$('.'+testedClassId+'Tab'), {activeClassName: 'active', triggerEvent: 'click'});
		if ($(testedClassId+'Loc')){
			var chartLoc = new MilkChart.Pie(testedClassId+'Loc', {
				width:425, 
				background:"rgba(255, 255, 255, 0)", 
				border:false, 
				strokeColor:"rgba(255, 255, 255, 0)",
				fontColor:'#FFF',
				fontSize:11,
				titleColor:'#FFF',
				chartTextColor:'#FFF'
			});
		}
		
		var chartOptions = {
			width:660, 
			background:"rgba(255, 255, 255, 0)", 
			border:false, 
			strokeColor:"rgba(255, 255, 255, 0)",
			fontColor:'#FFF',
			fontSize:11,
			titleColor:'#FFF',
			chartTextColor:'#FFF',
			lineWeight:3,
			padding:20,
			showLines:true, 
			showPoints:false				
		};

		var chartIds = [
            testedClassId+'TestChart', 
            testedClassId+'CoverageChart', 
            testedClassId+'ComplexityChart', 
            testedClassId+'RefactorChart', 
            testedClassId+'LocChart'
        ];		

		if ($(testedClassId+'HistoryTab')){
			$(testedClassId+'HistoryTab').addEvent('click', function(){		
				if ((!$($(testedClassId+'HistoryTabClicked')))&&(!$('history-for-'+testedClassId).hasClass('empty'))){
					chartIds.each(function(itemId){
						var data = JSON.decode($(itemId+'Data').get('text'));
						var chartLine = new MilkChart.Line($(itemId), chartOptions);
						chartLine.loadLocal(data);
						
						$(itemId).addEvent('click', function(){
							var chartClass = $(itemId).get('class');
							
							$(itemId).empty();
							
							var chartLine = new MilkChart.Line(itemId, chartOptions);
							switch(chartClass){
								case 'scatterLines':
									chartLine.setOptions({showLines:true, showPoints:false});
									$(itemId).set('class', 'lines');
									break;
								case 'lines':
									chartLine.setOptions({showLines:false, showPoints:true});
									$(itemId).set('class', 'scatter');
									break;
								default:
									 chartLine.setOptions({showLines:true, showPoints:true});
								 	$(itemId).set('class', 'scatterLines');
							}
							
							chartLine.loadLocal(data);						
						});
					});
					var clickTracker = new Element('span', {id:testedClassId+'HistoryTabClicked'}).inject("history-for-"+testedClassId);
				}
			});
		}
	});
});