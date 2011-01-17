jQuery(document).ready(function($){

	$("select").change(function () {
          var str = "";
          $("select option:selected").each(function () {
                str += $(this).text() + " ";
              });
          $("#period_rep").val(str);
        })
        .change();
	
	// Country Name AutoComplete	
	$("#country_name").focus().autocomplete(countries, {
		minChars: 0,
		autoFill: true,
		mustMatch: true,
		matchContains: false,
		scrollHeight: 180,
		formatItem: function(data, i, total) {
			// don't show the current month in the list of values (for whatever reason)
			return data[0];
		}
	});
	
	// show/hide the first alias depending on whether "Aliases" box is checked
	$('#has_alias').click(function() {
			// show or hide the alias block
			$('#cityAlias-0').toggle();			
			// set the right vals 
			//[** Don't really need this - just check if checked
			// and database accordingly **]
			if ($('#has_alias').attr('checked')) {				
				$('#has_alias').val('1');
			} else {
				$('#has_alias').val('0');
			}
        });

	$('#cityAlias').live('click', function(eve){
		eve.preventDefault();
		$.get("../addCityAlias", 
			function(html){
				if (html) {
					$('#cityAliasList').append(html);
				}
		});
		
	});
	
	$('#dropCityAlias').live('click', function(eve){
		eve.preventDefault();
		
		
		$.get("../dropCityAlias", 
			function(html){
				if (html) {
					$('#cityAliasList').hide();
					
					$('#city_alias').remove();
					//$('#cityAliasList').(html);
				}
		});
		
	});
	
});


// SAVE THIS FUNCTION....
function onSelectChange(selectEl){

	var id = selectEl.getAttribute('id');
	var val = selectEl.value;
	
	alert("ID is: " + id + " AND VAL: " + val);
	//document.preventDefault();
	$.get("../updatePeriod", 
	function(html){
		if (html) {
			$('#period_rep').load("ID is: " + id + " AND VAL: " + val);
		}
	}
	);
	
	
/*
			var divId = id.split("_");
			var id = divId[(divId.length-1)];
*/			var postArray = new Array();
			
			alert(" ID IS: "+id);
			postArray[id] = id;

/*$addAlias = $('<div>')
var $add_btn = $('<img />')
				.attr({
					'alt'   : msg['add_btn'],
					'title' : msg['add_title'],
					'src'   : options.p_add_img1
				})
				.mouseover(function (ev){
					$(ev.target).attr('src',options.p_add_img2);
				})
				.mouseout(function (ev){
					$(ev.target).attr('src',options.p_add_img1);
				})
				.click(function (){
					addPack(area_pack);
				})
				.appendTo($add_area);
		}
*/
}