<!-- maintain list of all the scripts needed for each view... -->
<!-- maybe make a list of partial script files needed for specific views later.... -->
<script src="<?php echo base_url();?>javascript/cufon.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>javascript/Steinem_400.font.js" type="text/javascript"></script>
<script type="text/javascript">
	//Cufon.replace('h1');
	//Cufon.replace('h2');
	//Cufon.replace('h3');
</script>
<!-- start jquery scripts -->
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery-1.3.2.min.js"></script>
<!-- jquery script for hover on thumbnail images-->
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.easing.1.3.js"></script>
<script type="text/javascript">
			$(document).ready(function(){
			$('.boxgrid.captionfull').hover(function(){
					$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:500});
				}, function() {
					$(".cover", this).stop().animate({top:'65px'},{queue:false,duration:500});
				});
			});
</script>
<!-- jquery script for star rating (jQuery.metadata plugin included -->
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.metadata.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.rating.pack.js"></script>
<!-- jquery script for tooltip -->
<script type='text/javascript' src='<?php echo base_url();?>javascript/jquery.tipsy.js'></script>
<!-- other jquery scripts -->
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery.url.packed.js"></script>
<!-- jQuery for city and theatre alias checkboxes -->
<script type="text/javascript" src="<?php echo base_url();?>javascript/aliasBox.js"></script>

<!-- other scripts -->
<script type="text/javascript" src="<?php echo base_url();?>javascript/ckeditor/ckeditor.js"></script>

<!-- jquery script for tabs -->
<script type="text/javascript">
$(document).ready(function() {	
/////

/////
		//When page loads...
		$(".tab_content").hide(); //Hide all content
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	
		//On Click Event
		$("ul.tabs li").click(function() {
	
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
	
			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
	});
	
	
	// If the anchor href for the navigation <li /> == the current page, 
	// add the 'current' class to it (the colored bar 'telling' user where s/he is) 
	$('#nav_header li').each(function(i) {
		//addClass("current");
		if ($(this).find("a").attr("href") == location) {
			//alert(i + " " + $(this).find("a").attr("href"));
			$(this).addClass("current");
		}
	});

});
	</script>
<!-- jquery script for tooltip -->	
<script type='text/javascript'>
	$(function() {
		$('.tipsy').tipsy();
	});
</script>

<!-- Google Analytics tracking code, added by MITH on 8/23/2011; should be last script in the list -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24127640-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>