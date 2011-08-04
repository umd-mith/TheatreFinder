jQuery(document).ready(function($){

	var urlString = "";
	var protocolPrefix = "http://";
	var urlHost = jQuery.url.attr("host");
	var port = jQuery.url.attr("port");
	port = (port) ? ":"+port : 0;
	
	// Get the root site, and segment 1 
	var siteRoot = jQuery.url.segment(0);
	// Typically, segment 1 will be my controller
	// e.g., "main" or "theatre_ctrl"
	var mainController = (jQuery.url.segment(1)) ? jQuery.url.segment(1) : "index";
	
	if (port!=0) {
		urlString = protocolPrefix+urlHost+port+"/"+siteRoot+"/";
	} else {
		urlString = protocolPrefix+urlHost+"/"+siteRoot+"/";
	}
	//
	var urlController = urlString+mainController+"/";
	// Image directory path (needed for aliasBox.js functions)
	var img_dir = urlString+"images/";
	/* ************************ */
    
    // Country Name AutoComplete
    // Data format for the pull-down
    function format(data){
        return data.name + " (" + data.id + ")";
    }
    $("#country_name").autocomplete('getCountries', {
    
        //width: 260,
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
	
	$(".theatre").mouseover(function(){
		$(".active").removeClass('active');
		$(this).addClass('active');
		// $.post(urlController+'featured_image', {'new_active': $(this).attr('id')}, function(data){
		// 			$("#featuredimage").attr("src",urlString+data);
		// 		});
		
		$.ajax({
			url: urlController+"featured_image",
	    	data: 'new_active='+$(this).attr('id'), 
			ajax: 'true',
			type: 'POST',
			dataType: 'json', 
			success: function(data){
				$("#featuredimage").attr("src",urlString+data);
			},
			failure: function(e) {
				$("#featuredimage").attr("src",urlString+data);
			}
		});

    });
	
	//overkill - can mouseover or click, and it calls the same function (see above)
	// $(".theatre").click(function(){
	// 		$(".active").removeClass('active');
	// 		$(this).addClass('active');
	// 		$.ajax({
	// 			url: urlController+"featured_image",
	// 	    	data: {'new_active':$(this).attr('id')}, 
	// 			ajax: 'true',
	// 			type: 'POST',
	// 			dataType: 'json', 
	// 			success: function(data){
	// 				$("#featuredimage").attr("src",urlString+data);
	// 			}
	// 		})
	// 
	//         });

    
});