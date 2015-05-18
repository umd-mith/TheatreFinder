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

	// hide the comment rows for each account entry till you need each of them
	$('.undoRow').hide();

// When the 'Submit' button for each 
$('.acctbutton').click(function() {
	
	// the id is the same as the account that is pending
	var acct_id = this.id;
	
	// the form attached to this button also has a similar id string
	var acct_data = $("#acct_form-"+acct_id).serialize();
	// and we serialize the data to get it in a "GET"/"POST" string format
	
	// Post it to the theatre_ctrl method, "approve_accounts"
	$.post(urlController+"theatres/approve_accounts",  // theatre_ctrl method to call-back
		   { acct_data : acct_data },	// serialized account data
   		   function(data){ // callback function to execute on success
				
				if (typeof(data.error_message) != 'undefined') { 
				// A little counter-intuitive:
				// If the data.error_message json element is NOT undefined,
				// we have an error in the database update. If the data.error_message elt stays undefined,
				// we're actually okay - because we have all the new account stuff defined.
	 				$("#message-" + acct_id).append("There has been an error for account[id]="+data.id+
	 				". DB Message= "+data.message+" </p><p>Please notify your Theatre Finder editor/administrator.");
	 			} else {
	 
						$("#wrapper_div-" + acct_id).hide();
	 					$('.pend').empty().append(data.new_pend_count);
	 					$("#message-" + acct_id).append("Updated User: <em>" + data.first_name + " " + 
						data.last_name + "</em> (username: <em>" + data.username +
	 					"</em>) to " + " access level: <strong>" +
	 					data.user_access_level + "</strong>. </p><p>Authorized by " +
	 					data.reviewing_admin +
	 					". If approved as a contributor, an activation email has been sent to <em>" +
	 					data.email_address +
	 					"</em></p><p>If denied, a regrets email has been sent.");
	 			}
	 			$("#undoRow-" + acct_id).show(); // poorly named undoRow, need to rename (someday)
	 		}, 
	 		'json'  // json-formmated return data
  	);		
})


$('.updatebutton').click(function() {
	// the id is the same as the account that is pending
	var acct_id = this.id;
	
	// the form attached to this button also has a similar id string
	var acct_data = $("#acct_form-"+acct_id).serialize();
	// and we serialize the data to get it in a "GET"/"POST" string format
	
	// Post it to the theatre_ctrl method, "update_accounts"
	$.post(urlController+"update_accounts",
		   { acct_data : acct_data },	// serialized account data
   		     function(data){ 
		   		
		   		if (typeof(data.error_message) != 'undefined') {
					// A little counter-intuitive:
					// If the data.error_message json element is NOT undefined,
					// we have an error in the database update. If the data.error_message elt stays undefined,
					// we're actually okay - because we have all the new account stuff defined.
					$("#message-" + acct_id).append("There has been an error for account[id]=" + data.id +
													". DB Message= " + data.message +
													" </p><p>Please notify your Theatre Finder editor/administrator.");
				} else {
					$("#wrapper_div-" + acct_id).hide();
					// update the new count
					$('#access_count').empty().append(data.new_count);
					$('#new_time').empty().append(data.last_reviewed_date);
					
					if (data.user_access_level == "DELETED") {
						
						$("#message-" + acct_id).append("User: <em>" + data.first_name + " " +
						data.last_name + "</em> (username: <em>" + data.username +
						"</em>) has been " + " <strong>" + data.user_access_level +
						"</strong>. </p><p>Authorized by " + data.reviewing_admin);
						$("#undo-"+acct_id).hide(); // no undo if you delete a user
						
					} else {
						$("#message-" + acct_id).append("Updated User: <em>" + data.first_name + " " +
						data.last_name + "</em> (username: <em>" + data.username +
						"</em>) to access level: <strong>" + data.user_access_level +
						"</strong>. </p><p>Authorized by " + data.reviewing_admin);
					}
				}
				$("#undoRow-" + acct_id).show();
		   },
		   
		   'json'  // json-formmated return data
  	);		
	
})

$('.undobutton').click(function() {
	var btn_string= this.id.split('-');
	var btn_id = btn_string[btn_string.length-1];
	
	var undo_data = $("#acct_form-"+btn_id).serialize();
	
	// Post it to the theatre_ctrl method, "update_accounts"
	$.post(urlController+"undo_update",
		   { acct_data : undo_data },	// serialized account data
   		     function(data){ 
		   		
		   		if (typeof(data.error_message) != 'undefined') {
					// A little counter-intuitive:
					// If the data.error_message json element is NOT undefined,
					// we have an error in the database update. If the data.error_message elt stays undefined,
					// we're actually okay - because we have all the new account stuff defined.
					$("#message-" + btn_id).append("There has been an error for account[id]=" + data.id +
													". DB Message= " + data.message +
													" </p><p>Please notify your Theatre Finder editor/administrator.");
				} else {
					
					$("#undoRow-" + btn_id).hide();
					$("#message-" + btn_id).empty(); // empty the #message element
					// update the new count (chain empty, then append
					$('#access_count').empty().append(data.new_count);
					$('#new_time').empty().append(data.last_reviewed_date);
					$('option:selected', '#select-'+ btn_id).removeAttr('selected');
					$('#select-'+ btn_id).val(data.user_access_level);
				}
				$("#wrapper_div-" + btn_id).show();
			},
		   'json'  // json-formmated return data
  	);		
})

});  // end document ready

/* *********** PARKING LOT functions ******************** */
/*	$('.account_types').change(function() {
	
	var id = this.id;
	var val = this.value;
	alert("I changed! "+ id + " AND VAL: " + val);
	
		$.ajax({
	 			url: urlController+"update_pending_accounts",
	 			data: 'account_info='+val+'::'+id,
	 			ajax: 'true',
	 			type: 'POST',
	 			dataType: 'text', //'json',
	 			success: function(data){
				
					//for (var i = 0; i < data.length; i++) {
						alert(data);
					//}
				}
		});
	});
*/
/*	$('.acct_form').submit(function() {
				
		var post_array = $(this).serializeArray();
  		//alert("Will Post: "+post_array);
		var post_str = '';
		jQuery.each(post_array, function(i, field){
        post_str += field.name + "::" + field.value + "-";
      });
  
  	  var post_str = $(this).serialize();
	  alert(post_str);
	  
*/
		//var post_data 
  		//return false;
		
		/* $.ajax({
	 			url: urlController+"update_pending_accounts",
	 			data: 'post_elts='+post_str, //'account_info='+val+'::'+id,
	 			ajax: 'true',
	 			type: 'POST',
	 			dataType: 'text', //'json',
	 			success: function(data){
				
					//for (var i = 0; i < data.length; i++) {
						alert(data);
					//}
				}
		}); */
//	});
	
/*	$('.acct__button').click(function() {
	
	var id = this.id;
	var val = this.value;
	//var access_level = $('#select-'+id).attr('value');
	
	alert("CLICKED! "+ id + " AND VAL: " + val + " AND SELECTED: ");
	
	$('.account_types').each(function(){
         var sel_id = this.id;
		 var select_val = this.value;
		 alert("Got "+ sel_id +  " Value: " + select_val);
	 });
	
	})
*/
