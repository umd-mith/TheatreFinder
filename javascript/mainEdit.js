jQuery(document).ready(function($){

	var urlString = "";
	var protocolPrefix = "http://";
	var urlHost = jQuery.url.attr("host");
	var port = jQuery.url.attr("port");
	port = (port) ? ":"+port : 0;
	
	var siteRoot = jQuery.url.segment(0);
	var mainController = jQuery.url.segment(1);
	
	if (port!=0) {
		urlString = protocolPrefix+urlHost+port+"/"+siteRoot+"/";
	} else {
		urlString = protocolPrefix+urlHost+"/"+siteRoot+"/";
	}
	//
	var urlController = urlString+mainController+"/";
	// Image directory path (needed for aliasBox.js functions)
	var img_dir = urlString+"images/";
	
	
	// Ensure the initial divs are hidden in Adding a new form, 
	// to avoid ghost views of the divs onDocumentLoad
	$('#theatreAliasDiv_1').hide();
	
	
	// check theatre_alias checkbox details
	$('#theatre_aliasCB').theatreChkBoxCheck($('#theatreAliasDiv_1'), urlController, img_dir);
	$('#theatre_aliasCB').addFormTxtBox($('#theatreAliasDiv_1'), "theatre_aliases", img_dir, urlController);
	
	// check to determine if the cityAlias Checkbox is checked or not
	$('#cAliasCB').chkBoxCheck($('#cityAliasDiv_1'), urlController, img_dir);
	// set up the cityAlias Checkbox and add the "addFormTxtBox" function to it
	$('#cAliasCB').addFormTxtBox($('#cityAliasDiv_1'), "cityAliases", img_dir, urlController);
	
	// Add the star rating from the hidden input, id="db_star_rating" 
	// (set by the php controller, edit_visitor_form, from the theatre's existing rating in the db
	var db_rating = $('#db_star_rating').val();	
	$('.star').rating('select', db_rating);
	
	/* ************************ */
	
    // Country Name AutoComplete
    // Data format for the pull-down
    function format(data){
        return data.name + " (" + data.id + ")";
    }
    $("#country_name").autocomplete(urlController+'getCountries', {
    
        minChars: 0,
        //max: 12,
        //autoFill: true,
        mustMatch: true,
        scrollHeight: 220,
        parse: function(data){
            return $.map(eval(data), function(row){
                return {
                    data: row,
                    value: row.name,
                    result: row.name
                }
            });
        },
        formatItem: function(item){
            return format(item);
        }
    });
	
		$("#periods").change(function() {
		$.ajax({
			url: urlController+"changeTypeList",
	    	data: 'periods='+$(this).val(), 
			ajax: 'true',
			type: 'POST',
			dataType: 'json', 
			success: function(data){
				var options = '';
				for (var i = 0; i < data.length; i++) {
					// If 'Other' is chosen, we need a text box input 
					// so a new 'type' might be created
					if (data[i].t_type == 'UniqueOther') {
						if ($('#sub_type').length > 0) {
							$("#sub_type").remove();
						}
						//alert("appending to: "+$('#type_wrapper').id);
						$('#type_wrapper').append('<input id="sub_type" name="sub_type" type="text" size"20" max="64"></input>');
					} else { // we can get our 'sub'type of theatre from the main period category
						if ($('select#sub_type').length > 0) {
							options += '<option value="' + data[i].t_type + '">' +
							data[i].t_type + '</option>';
						} else {
							$('input#sub_type').remove();
							$('#type_wrapper')
								.append($('<select id="sub_type" name="sub_type"></select>'));
								options += '<option value="' + data[i].t_type + '">' +
									data[i].t_type +'</option>';
								
						}
					}
				}
				$("#sub_type").html(options);
			}
    	})
	});
    
});

