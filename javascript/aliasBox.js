/* jQuery to build a list of text boxes that can be added/removed
 * based on check box to include them or based on their add/delete buttons
 * Mainly used to enable adding aliases to certain identifiers (e.g., city -> city alias names;
 * theatre -> theatre alias names; etc.)
 *
 * Consists of:
 * 1) chkBoxCheck() == sets up the checkbox (for city aliases)
 * 2) theatreChkBoxCheck() == sets up checkbox (theater_aliases)
 * 3) addFormTxtBox() == adds the textbox 'package/object' of divs, based on chkbox
 * 		clicks or user interaction with add/del buttons
 * 4) addRow() adds a row of textbox div package
 * 5) delRow() deletes a row
 * 
 */ 
jQuery.fn.chkBoxCheck = function(initDivId, controllerURL, img_dir) {
	
	var chkBox = jQuery(this);
	var num = chkBox.attr('value');
	var classString = initDivId.attr('class');
	var divIdString = initDivId.attr('id');

	// if I'm loading my initial checkBox, and it is checked,
	// then I have some aliases for this already in the database
	if (chkBox.is(':checked')) {
		//alert("ID ["+chkBox.attr('id')+"] Has been CHECKED. Num="+num);
    	
		if (num == 0) {
			initDivId.children().show();
		   	initDivId.show();
			
		} else {
			// I have alias(es) for this city
			numList = num.split("_");
			//alert("CheckBox cAliasCB value="+numList[0]+" CITY_id["+numList[1]+"]");
			// Set up the AJAX call
		   $.ajax({
              type: "POST",
		      url: controllerURL+"getCityAliases",
              async: false,
			  data: {
			  	cityId: numList[1]
			  },
              success: function(xml) {
			  	var aliasData ='';
				var cityAlias = '';
				var aliasDivs = new Array();
				// Within context of aliasData xml tag,
				// walk through each element (cityAlias)
				$("aliasData",xml).each(function(id) {
					// get the xml tag's index (id)
					aliasData = $("aliasData",xml).get(id);
					// now get the cityAlias tag's text within the cityAlias child tag
					// of aliasData xml tag
					cityAlias = $("cityAlias",aliasData).text();
					
					if (id == 0) { // just have one, show the initial hidden cityAliasDiv_1
						initDivId.children("input[type=text]").attr("value", cityAlias);
						initDivId.children().show();
						initDivId.show();
					}
					else if (id>0){
						aliasDivIdString = initDivId.attr('id');
						aliasDivs = aliasDivIdString.split("_");
						aliasPrefix = aliasDivs[0];
						// clone the original - id=previous div id num, e.g., 1 for cityAliasDiv_1
						// this is the hack...why couldn't I just use addRow()...?
						cloneTxtBox(aliasPrefix, id, chkBox, img_dir, controllerURL);
						$("#" + aliasPrefix+"_"+(id+1)).children("input[type=text]").attr("value", cityAlias);
					}
				  });
      		 	}
   			});
		} 
    } else {
		// * Need to check and remove any of the existing divs,
		$("." + classString).each(function(){
                var id = this.id;
                $("input:reset");
                var idArr = id.split("_");
                //var isIt = $.isArray(idArr);
                if (idArr[1] > 1) {
                
                    $("input:reset");
                    $('#' + id).empty();
                    $('#' + id).remove();
                }
            });
		// then hide the initial template one		
		initDivId.children("input[type=text]").attr("value", '');
		initDivId.hide();

	}
}

// THEATRE alias checkbox (*Differs primarily in callback* when compared with
// original callback)
jQuery.fn.theatreChkBoxCheck = function(initDivId, controllerURL, img_dir) {
	
	var chkBox = jQuery(this);
	var num = chkBox.attr('value');
	var classString = initDivId.attr('class');
	var divIdString = initDivId.attr('id');
	
	// if I'm loading my initial checkBox, and it is checked,
	// then I have some aliases for this already in the database
	if (chkBox.is(':checked')) {
    	if (num == 0) {
			initDivId.children().show();
		   	initDivId.show();
		} else {
			// I have alias(es) for this city
			var theatre_id = $('#theatre_id').attr("value");
			//alert("CheckBox value="+num+" and Theatre Id: "+theatre_id);
			
			// Set up the AJAX call
		   $.ajax({
              type: "POST",
		      url: controllerURL+"get_theatre_aliases",
              async: 'true',
			  data: {
			  	theatre_id: theatre_id
			  },
			  dataType: 'json',
              success: function(data){
			  	for (var i = 0; i < data.length; i++) {
			  		if (i == 0) {
			  			//alert("ONE Alias [" + data[i].theatre_alias + "]");
			  			initDivId.children("input[type=text]").attr("value", data[i].theatre_alias);
			  			initDivId.children().show();
			  			initDivId.show();
			  		}
			  		else {
			  			//alert("Alias[" + i + "]=[" + data[i].theatre_alias + "]");
						aliasDivIdString = initDivId.attr('id');
						aliasDivs = aliasDivIdString.split("_");
						aliasPrefix = aliasDivs[0];
						// clone the original - id=previous div id num, e.g., 1 for cityAliasDiv_1
						// this is the hack...why couldn't I just use addRow()...?
						cloneTxtBox(aliasPrefix, i, chkBox, img_dir, controllerURL);
						$("#" + aliasPrefix+"_"+(i+1)).children("input[type=text]").attr("value", data[i].theatre_alias);
			  		}
			  	}
			  }		
   			}); 
		} 
    } else { // checkbox is NOT checked
		// * Need to check and remove any of the existing divs,
		$("." + classString).each(function(){
                var id = this.id;
                $("input:reset");
                var idArr = id.split("_");
                //var isIt = $.isArray(idArr);
                if (idArr[1] > 1) {
                
                    $("input:reset");
                    $('#' + id).empty();
                    $('#' + id).remove();
                }
            });
		// then hide the initial template one		
		initDivId.children("input[type=text]").attr("value", '');
		initDivId.hide();
	}
}


// Function to add aliases to a form based on checking box
jQuery.fn.addFormTxtBox = function(aliasDivId, aliasClassString, img_dir, controllerURL) {

	var chkBox = jQuery(this);
	var aliasIdString = aliasDivId.attr('id');

	// init all stuff for add/delete buttons	
	var delBtnImg = img_dir + 'icon_delete.png';
	var delBtnimg2 = img_dir + 'icon_deleteHover.gif';
	var addBtnImg = img_dir + 'icon_add.png';
	var addBtnImg2 = img_dir + 'icon_addHover.gif';
	// array of alt texts for images	
	var altTxt = {
		'add_btn': 'Add button',
		'add_title': 'add an alias',
		'del_btn': 'Delete button',
		'del_title': 'remove this alias',
	};
	
	var add_btn=$("#"+aliasIdString).children("#add_btn");
	var del_btn=$("#"+aliasIdString).children("#del_btn");
	
	add_btn.mouseover(function(ev){
		$(ev.target).attr('src', addBtnImg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', addBtnImg);
	}).click(function(){
		addRow($(this).parent().attr('id'), aliasClassString, chkBox, img_dir, controllerURL);
	}).addClass('icon')
	.appendTo($("#"+aliasIdString)); // aliasDivId
	
	
	del_btn.mouseover(function(ev){
		$(ev.target).attr('src', delBtnimg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', delBtnImg);
	}).click(function(ev){
		delRow($(this).parent().attr('id'), aliasClassString, chkBox, controllerURL);
	}).addClass('icon')
	  .appendTo($("#"+aliasIdString)); // aliasDivId
	
	chkBox.click(function(){
		
		var num = chkBox.attr('value');
		if (num != 0) { // there are aliases for this city
			var numList = num.split('_');
			// now re-assign num to the real number of aliases
			num = numList[0];
		}
		if (chkBox.is(':checked')) {
			// If the chkBox is checked & 
			// there are no aliases already set/showing from the model/controller
			// need to create the aliasId via idString vs. selector/element object
			// passed in because of the way I delete the alias elements
			$("#"+aliasIdString).children("input[type=text]").attr("value", '');
			$("#"+aliasIdString).children().show();
			$("#"+aliasIdString).show();
			var newNum = new Number(num + 1);
			chkBox.attr('value', newNum);

		}
		else { // chkBox is UNchecked
		
			// need to remove the alias list
			$("." + aliasClassString).each(function(){
				var id = this.id;
				$("input:reset");
				var idArr = id.split("_");
				
				if (idArr[1] > 1) { // cleanu-up/remove every aliasClass div 
									// except the First one 
									// i.e., don't remove the clone-template (the First idArr[1])
					$("input:reset");
					$('#' + id).empty();
					$('#' + id).remove();
				}
				$('#clear_'+idArr[1]).remove();
			});
			
			$("#"+aliasIdString).children("input[type=text]").attr("value", '');
			$("#"+aliasIdString).hide();

			chkBox.attr('value', 0);
			chkBox.attr('checked', false);
		}
		
	});
	
}

/* *****************************************************************
 * Function:	addRow
 * Input:		1) div id for the curr alias e.g., cityAlias_1
 * 				2) class string for the alias, e.g., "cityAliases"
 * 				   (note: could also get this by computing from div obj
 * 				3) chkBox obj - only needed here as delRow() arg
 * 
 * Output:		Adds to the end of the existing rows of aliases (regardless
 * 				of which row was clicked, always adds at end
 * 				Also adds click handler to the btn images
 * 				[Note: is there a way to make sure this happens via
 * 				 the clone of the <div>...? Didn't see, to follow it
 * 
 ***************************************************************** */
function addRow(aliasDivId, aliasClassString, chkBox, img_dir, controllerURL){

	var num = $("."+aliasClassString).length;
	
	// how many "duplicatable" input fields we currently have
	var newNum = new Number(num + 1);
	// the numeric ID of the new input field being added
	var DivString = aliasDivId.split("_");
	
	// create the new element via clone(), and manipulate it's ID using newNum value
	var newElem = $('#'+DivString[0]+'_' + num).clone().attr('id', DivString[0]+'_' + newNum);
	
	// manipulate the name/id values of the input inside the new element
	newElem.children("input[type=text]").attr('id', DivString[0]+'_' + newNum).attr('value', '');
	// label	
	newElem.children("label").attr('for', DivString[0]+'_' + newNum);
	newElem.children("label").text('Alias ' + newNum + ":");
	
	var delBtnImg = img_dir + 'icon_delete.png';
	var delBtnimg2 = img_dir + 'icon_deleteHover.gif';
	var addBtnImg = img_dir + 'icon_add.png';
	var addBtnImg2 = img_dir + 'icon_addHover.gif';
	
	var del_btn = newElem.children("#del_btn");
	var add_btn = newElem.children("#add_btn");
	
	// add the handlers for the add/delete buttons
	add_btn.mouseover(function(ev){
		$(ev.target).attr('src', addBtnImg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', addBtnImg);
	}).click(function(){
		//alert("ID of target: " + $(this).parent().attr('id'));
		addRow($(this).parent().attr('id'), aliasClassString, chkBox, img_dir, controllerURL);
	});
	
	del_btn.mouseover(function(ev){
		$(ev.target).attr('src', delBtnimg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', delBtnImg);
	}).click(function(ev){
		delRow($(this).parent().attr('id'), aliasClassString, chkBox, controllerURL);
	})
	
	// insert the new element after the last existing input field
	$('#'+DivString[0]+'_' + num).after(newElem);
	$('#'+DivString[0]+'_' + num).after("<div class=\"clear\" id=\"clear_"+num+"\"></div>");
	chkBox.attr('value', newNum);
}

/* *****************************************************************
 * Function:	delRow
 * Input:		1) div id for the alias e.g., cityAlias_1
 * 				2) class string for the alias, e.g., "cityAliases"
 * 				   (note: could also get this by computing from div obj
 * 				3) the checkbox that was checked
 * 
 * Output:		Deletes the row that was clicked, adjusts numbers of rows
 * 				that are higher as needed.  (e.g., if row 2 was clicked and
 * 				rows 3, 4, 5 existed, they would each be re-identified as rows 2,3,4)
 * 
 ***************************************************************** */
function delRow(aliasDivId, aliasClassString, chkBox, controllerURL) {
	
	var divInputVal = $('#'+aliasDivId).children("input[type=text]").attr("value");
	var theatre_id = $("#theatre_id").attr("value");
	var city_id = $("#city_id").attr("value");

	if (divInputVal != '') {
		$.ajax({
			type: 'POST',
			url: controllerURL+"delete_alias", //"../delete_alias, <==also works, but using controller var is better
			data: {
				alias_text: divInputVal,
				theatre_id: theatre_id,
				city_id: city_id,
				alias_type: aliasClassString
			},
			success: function(data){
				
				var DivString = aliasDivId.split("_");
				var num = DivString[1];
				var oneLess = new Number(num - 1);
				var len = $("." + aliasClassString).length;
				
				if (len == 1) { // don't delete the first one, just clear/hide it
					
					$('#' + aliasDivId).children("input[type=text]").attr("value", '');
					$('#' + aliasDivId).hide();
					$('.clear').each(function(){
						var id = this.id;
						var idArr = id.split("_");
						//if the .clear class div has an id like clear_n where n is the row
						// remove it
						if (idArr[1] > 0) {
							$('#' + id).remove();
						}
					});
					
					chkBox.attr('value', 0);
					chkBox.attr('checked', false);
				}
				else {
				
					$("." + aliasClassString).each(function(){
						var id = this.id;
						var idArr = id.split("_");
						//var isIt = $.isArray(idArr);
						if (idArr[1] > num) {
							newNum = idArr[1] - 1;
							//alert("NewNum: "+newNum+"Curr Id: "+ DivString[0]+"_"+idArr[1])
							// reduce nums for all the children within the div with id higher than current deleted one
							$("#" + DivString[0] + "_" + idArr[1]).children("label").attr('for', DivString[0] + '_' + newNum);
							$("#" + DivString[0] + "_" + idArr[1]).children("label").text('Alias ' + newNum + ":");
							$("#" + DivString[0] + "_" + idArr[1]).children("input[type=text]").attr('id', DivString[0] + '_' + newNum);
							// now reduce the num for the id of the div itself
							$("#" + DivString[0] + "_" + idArr[1]).attr('id', DivString[0] + "_" + newNum);
						}
					});
					// now safely empty and remove the div
					$('#' + aliasDivId).empty();
					$('#' + aliasDivId).remove();
					// remove the 'clear' class div that ends aliasDiv
					$('#clear_' + num).remove();
					// reduce chkBox value by one
					chkBox.attr('value', oneLess);	
				}
			}
		});
	} else { // the text box is empty, so just remove it
	
		var DivString = aliasDivId.split("_");
		var num = DivString[1];
		var oneLess = new Number(num - 1);
		var len = $("." + aliasClassString).length;
				
		if (len == 1) { // don't delete the first one, just clear/hide it
					
			$('#' + aliasDivId).children("input[type=text]").attr("value", '');
			$('#' + aliasDivId).hide();
			$('.clear').each(function(){
				var id = this.id;
				var idArr = id.split("_");
				//if the .clear class div has an id like clear_n where n is the row
				// remove it
				if (idArr[1] > 0) {
					$('#' + id).remove();
				}
			});
					
				chkBox.attr('value', 0);
				chkBox.attr('checked', false);
		} else {
				
			$("." + aliasClassString).each(function(){
				var id = this.id;
				var idArr = id.split("_");
				if (idArr[1] > num) {
					newNum = idArr[1] - 1;
					// reduce nums for all the children within the div with id higher than current deleted one
					$("#" + DivString[0] + "_" + idArr[1]).children("label").attr('for', DivString[0] + '_' + newNum);
					$("#" + DivString[0] + "_" + idArr[1]).children("label").text('Alias ' + newNum + ":");
					$("#" + DivString[0] + "_" + idArr[1]).children("input[type=text]").attr('id', DivString[0] + '_' + newNum);
					// now reduce the num for the id of the div itself
					$("#" + DivString[0] + "_" + idArr[1]).attr('id', DivString[0] + "_" + newNum);
				}
			});
			// now safely empty and remove the div
			$('#' + aliasDivId).empty();
			$('#' + aliasDivId).remove();
			// remove the 'clear' class div that ends aliasDiv
			$('#clear_' + num).remove();
			// reduce chkBox value by one
			chkBox.attr('value', oneLess);
		}
	}
}

/* *****************************************************************
 * Function:	cloneTxtBox
 * Input:		1) prefix for the alias div e.g., "cityAlias_"
 * 				2) num of alias div to clone
 * 				3) chkBox obj
 * 				4) img_dir path
 *
 * 
 * Output:		cloned text box with blank value
 * 				The value will change based on the calling function
 * 				( chkBox.chkBoxCheck() )
 * 				This is a hack...Not sure how I could
 * 				do it to get addRow() to be the function called
 * 				from chkBoxCheck()....
 * 
 ***************************************************************** */
function cloneTxtBox(prefix, num, chkBox, img_dir, controllerURL) {

	// how many "duplicatable" input fields we currently have
	var newNum = new Number(num + 1);

	var aliasClassString = $("#"+prefix+"_"+num).attr('class');
	var longAliasClassString = aliasClassString.split(" ");
	aliasClassString = longAliasClassString[0]; // just need the first part of class string
	
	// create the new element via clone(), and manipulate it's ID using newNum value
	var newElem = $('#'+prefix+'_' + num).clone().attr('id', prefix+'_' + newNum);
	
	// manipulate the name/id values of the input inside the new element
	newElem.children("input[type=text]").attr('id', prefix+"_"+newNum).attr('value', '');
	// label	
	newElem.children("label").attr('for', prefix+"_"+newNum);
	newElem.children("label").text('Alias ' + newNum + ":");
	
	var delBtnImg = img_dir + 'icon_delete.png';
	var delBtnimg2 = img_dir + 'icon_deleteHover.gif';
	var addBtnImg = img_dir + 'icon_add.png';
	var addBtnImg2 = img_dir + 'icon_addHover.gif';
	
	var del_btn = newElem.children("#del_btn");
	var add_btn = newElem.children("#add_btn");
	
	// add the handlers for the add/delete buttons
	add_btn.mouseover(function(ev){
		$(ev.target).attr('src', addBtnImg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', addBtnImg);
	}).click(function(){
		addRow($(this).parent().attr('id'), aliasClassString, chkBox, img_dir, controllerURL);
	});
	
	del_btn.mouseover(function(ev){
		$(ev.target).attr('src', delBtnimg2);
	}).mouseout(function(ev){
		$(ev.target).attr('src', delBtnImg);
	}).click(function(ev){
		delRow($(this).parent().attr('id'), aliasClassString, chkBox, controllerURL);
	});
	// insert the new element after the last existing input field
	$('#'+prefix+'_' + num).after(newElem);
	$('#'+prefix+'_' + num).after("<div class=\"clear\" id=\"clear_"+num+"\"></div>");
	chkBox.attr('value', newNum);
}
