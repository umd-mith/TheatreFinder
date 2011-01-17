<!-- slider details associated with period.php view (main/period controller/method) -->
<!-- also the boxgrid jQuery associated with most of the main files -->

<script type="text/javascript">
			$(document).ready(function(){
			$('.boxgrid.captionfull').hover(function(){
					$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:500});
				}, function() {
					$(".cover", this).stop().animate({top:'65px'},{queue:false,duration:500});
				});
			});
</script>
<!-- slider code -->
<!-- **TODO: Do we need to get this jqueryui.com code on server/locally?? -->
<link type="text/css" href="http://jqueryui.com/themes/base/ui.all.css" rel="stylesheet" />
	<script type="text/javascript" src="http://jqueryui.com/ui/ui.core.js"></script>
	<script type="text/javascript" src="http://jqueryui.com/ui/ui.slider.js"></script>
<script type="text/javascript">
	$(function() {
		$("#slider-range").slider({
			orientation: "vertical",
			range: true,
			values: [40, 85],
			slide: function(event, ui) {
				$("#amount").val(ui.values[0] + ' - ' + ui.values[1]);
			}
		});
		$("#amount").val($("#slider-range").slider("values", 0) + ' - ' + $("#slider-range").slider("values", 1));
	});
</script>
<!-- end slider code -->
<script type="text/javascript">
		$(document).ready(function() {
	
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

});
	</script>