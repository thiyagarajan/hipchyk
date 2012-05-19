
function social_links_ajax_saveOrder(){
	$('sortOrderData').value = Sortable.serialize("displayDiv");
}

function selectionChanged(){


	var label = $("instruction");
	var dropdown = $("networkDropdown");
	var settingInput = $("addSettingInput");
	var addButton = $("addButton");
	
	if(dropdown.selectedIndex != 0){
		var currentSelection = dropdown.options[dropdown.selectedIndex]
		label.innerHTML = currentSelection.getAttribute('instruction');
		settingInput.value = '';
		addButton.disabled = false;
	}	
	else{
		label.innerHTML = '';
		settingInput.value = '';
		addButton.disabled = true;
	}
	
}

function onTextKeyDown(){
	if(event.keyCode == 13){
		social_links_ajax_addNetwork(
			document.getElementById('networkDropdown').selectedIndex - 1,
			document.getElementById('addSettingInput'),
			document.getElementById('responseDiv')
		);
	}
}

function onDropToTrash(){
	var span = $('trash').firstChild.nextSibling;
	if(span){
		social_links_ajax_delete_network(span.id.split('_')[1]);
		span.parentNode.removeChild(span);
	}
	
}

function addLoadingIcon(){
	var img = Builder.node('span',{
						id:'loadingImage',
						className:'linkWrapper',
						title:'Loading',
						style:'position:relative;'},
						Builder.node('img',{src:'/wp-content/plugins/social-links/images/ajax-loader.gif',style:'margin:2px'},''));
	//console.log(img);
	$('displayDiv').appendChild(img);
}

function social_links_ajax_addNetwork(selectedIndex,textInput,responseDiv){
	addLoadingIcon();
 	var siteID = $('networkDropdown').options[selectedIndex].value;
 	var mysack = new sack(getWordpressBaseLocation()+"wp-admin/admin-ajax.php" );    
	
	//console.log('Adding network '+siteID+ ': '+textInput);
	
	mysack.execute = 1;
	mysack.method = 'POST';
	mysack.setVar( "action", "social_links_add_network" );
	mysack.setVar( "siteID", siteID );
	mysack.setVar( "value", textInput.value );
	mysack.encVar( "cookie", document.cookie, false );
	mysack.onError = function() { alert('Ajax error while adding new network' )};
	mysack.runAJAX();
	
	
  	return true;

}

function social_links_ajax_delete_network(linkId){
	
	var mysack = new sack(getWordpressBaseLocation()+"wp-admin/admin-ajax.php" );    
	
	mysack.execute = 1;
	mysack.method = 'POST';
	mysack.setVar( "action", "social_links_delete_network" );
	mysack.setVar( "linkId", linkId );
	mysack.encVar( "cookie", document.cookie, false );
	mysack.onError = function() { alert('Ajax error while adding new network' )};
	
	mysack.runAJAX();
	
	createSortables();
	
	return true;
}

function createSortables(){
	Sortable.destroy('displayDiv');
	Sortable.destroy('trash');

	targets = $$('.drop_target');
		Sortable.create('trash',{tag:'span',containment:targets,constraint:false,dropOnEmpty:true,
		onUpdate: function(){
			onDropToTrash();
		}
	});
	Sortable.create('displayDiv',{tag:'span',containment:targets,overlap:'horizontal',constraint:false});

}

function getWordpressBaseLocation(){
	return $('callBackUrl').value.split('wp-content/')[0];
}
   