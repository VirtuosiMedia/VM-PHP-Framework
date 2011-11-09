/**
 * Handles all of the interactions for the unit test suite
 */
window.addEvent('domready', function(){
	var myTips = new Tips('.tips', {text:'rel', 'className':'tipContainer'});
	var mySmoothScroll = new Fx.SmoothScroll({links:'.topLink, .classLink', offset:{'x':0, 'y':-80}});
	var myMultiSelect = [];
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
	
	//Take care of the form filtering and UI
	$$('.multiSelect').each(function(item, index){
		var selectTitle = item.getElement('h3').get('text').toLowerCase();
		item.getElement('h3').dispose();
		var options = {
			defaultDisplayText:['all '+selectTitle],
			singleDisplayText:[selectTitle.substring(0, selectTitle.length-1)],
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

	var hasClasses = function(item, currentListId, lists){
		var listIds = [];
		var itemClasses = item.get('class').split(' ');
		var itemVisible = (item.getStyle('display') != 'none') ? true : false;
		var itemParentId = item.getParent().get('id');
		console.log(item.getElement('input'));
		var itemChecked = item.getElement('input').get('checked');
		var totalChecked = [0,0,0];
		var numLists = lists.length;
		var containsClasses = [];
		
		for (i=0; i<numLists; i++){
			if ($(lists[i])){
				if (currentListId == itemParentId){ 
					containsClasses[i] = (itemVisible||itemChecked) ? true : false;
				} else if (lists[i] == itemParentId){
					containsClasses[i] = true;
				} else {
					containsClasses[i] = (itemChecked) ? true : false;
				}
				
				listIds[i] = [];
				$$('#'+lists[i]+' input').each(function(box, j){
					if (box.get('checked')){
						listIds[i][j] = box.get('id');
						totalChecked[i] += 1;
					}
				});
				
				if (totalChecked[i] == 0){
					containsClasses[i] = true;
				}
			}
		}

		for (i=0; i<numLists; i++){
			listIds[i].each(function(listId){
				containsClasses[i] = (itemClasses.contains(listId)) ? true : containsClasses[i];
			});
		}

		//Check if the other lists are empty
		var index = listIds.indexOf(itemParentId);
		var othersEmpty = true;
		for (i=0; i<numLists; i++){
			if (i != index){
				othersEmpty = (totalChecked[i] == 0) ? othersEmpty : false;
				containsClasses[i] = (othersEmpty) ? true : containsClasses[i];
			}
		}
		containsClasses[index] = (othersEmpty) ? true : containsClasses[index];
		
		return (containsClasses.contains(false)) ? false : true;
	};
	
	$$('.selectList input').addEvent('change', function(e){
//		e.stop();
		var checked = false;
		var currentListId = this.getParent('ul').get('id');
		
		if (this.get('checked')){
//			this.set('checked', false);
		} else {
	//		this.set('checked', 'checked');
			checked = true;
		}
		
		allUnchecked = true;
		
		$$('.selectList input').each(function(checkbox){
			allUnchecked = (checkbox.get('checked')) ? false : allUnchecked;
		});
		
		$$('.selectList li').each(function(listItem){
			if ((allUnchecked)||(hasClasses(listItem, currentListId, ['groupsList', 'subgroupsList', 'authorsList']))){
				listItem.setStyle('display', 'block');
			} else {
				listItem.setStyle('display', 'none');
			}
		});
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