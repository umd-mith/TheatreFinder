<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Theatre Finder | Theatre entry</title>
<link rel="stylesheet" href="<?= base_url();?>css/reset.css" />
<link rel="stylesheet" href="<?= base_url();?>css/text.css" />
<link rel="stylesheet" href="<?= base_url();?>css/960.css" />
<link rel="stylesheet" href="<?= base_url();?>css/theatrefinder.css" />
<link rel="stylesheet" href="<?= base_url();?>javascript/jquery.rating.css" />
<link type="text/css" href="<?= base_url();?>css/tabs.css" rel="stylesheet" />
<!--[if IE 6]>
<link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection">
<![endif]--> 

<script src="<?= base_url();?>javascript/cufon.js" type="text/javascript"></script>
<script src="<?= base_url();?>javascript/steinem_400-steinem_700-steinem_italic_700-steinem_italic_700.font.js" type="text/javascript"></script>
<script type="text/javascript">
	Cufon.replace('h1');
	//Cufon.replace('h2');
	//Cufon.replace('h3');
</script>
<!-- start jquery scripts -->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery-1.3.2.min.js"></script>
<!-- jquery script for hover on thumbnail images-->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.easing.1.3.js"></script>
<script type="text/javascript">
			$(document).ready(function(){
			$('.boxgrid.captionfull').hover(function(){
					$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:500});
				}, function() {
					$(".cover", this).stop().animate({top:'65px'},{queue:false,duration:500});
				});
			});
</script>
<!-- jquery script for star rating -->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.rating.pack.js"></script>
<!-- jquery script for tabs -->
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
</head>

<body id="entry">

<div class="container_12 clearfix">
    	<!-- Navigation area -->
        <div class="grid_12 nav">   
            <ul>
	         	<li class="grid_2 alpha logo"><h1><a href="index.html">Theatre Finder</a></h1></li>
                <li class="grid_2 nav_1"><h2><a href="index.html">home</a></h2>
                	<p>Return to the main page</p>
                </li>
                <li class="grid_2 nav_2"><h2><a href="location.html">location</a></h2>
                	<p>Search for a theatre by country, region or city</p>
                </li>
                <li class="grid_2 nav_3"><h2><a href="type.html">theatre type</a></h2>
                	<p>Search for a theatre by architectural type</p>
                </li>
                <li class="grid_2 nav_4"><h2><a href="period.html">period</a></h2>
                	<p>Search for a theatre by historical period or date</p>
                </li>
                <li class="grid_2 omega nav_5"><h2><a href="researchers.html">researchers</a></h2>
                	<p>Edit theatre details and create CAMP models</p>
                </li>
			</ul>
         </div>     
       
       <!-- Search area -->
       <div class="grid_12 searchbar">   
            <div class="grid_8 alpha">
            	<h2>A comprehensive, web-based, world-wide guide to all theatres over 100 years old <a class="beta">Beta</a></h2>
            </div>
            <div class="grid_4 omega">
            	<form class="search">	
                    <select>
                        <option>All</option>
                        <option>Location</option>
                        <option>Type</option>
                        <option>Period</option>
                        <option>Name</option>
                    </select>
                    <input type="text" size="16" name="search" />
                    <input type="submit" name="submit" value="Search" />
                </form>
            </div>
            
       </div>
       
       <div class="grid_12 breadcrumbs"><p>Theatre entry > Theatre name</p></div>
       
       <!-- Featured theatres -->
       <div class="grid_12 featuredarea">   
            <div class="grid_12 alpha omega theatreName"><h2><?=$theatre->theatre_name;?></h2></div>
			<div class="grid_3 alpha sidebar">
            	<div class="entryDetails">
                    
                        <h4 class="active"><a href="#">Overview</a></h4>
                      <!--  	<ul>
                            	<li>Rating</li>
                                <li>Name</li>
                                <li>Type</li>
                                <li>Country</li>
                                <li>Region</li>
                                <li>City</li>
                            </ul>
                        -->
						<h4><a href="#">History</a></h4>
                        	<ul>
                            	<li>Chronology</li>
                                <li>Significance</li>
                                <li>Quotes and literary references</li>
                            </ul>
                        <h4><a href="#">Architecture</a></h4>
                        	<ul>
                            	<li>Existing structure</li>
                                <li>Most recent restoration</li>
                                <li>Original structure</li>
                                <li>Major renovations and restorations</li>
                                <li>Measurements</li>
                            </ul>
                        <h4><a href="#">Getting there</a></h4>
                        	<ul>
                                <li>Official website</li>
                                <li>Helpful websites</li>
                                <li>Access notes</li>
                                <li>GPS coordinates</li>
                            </ul>
                        <h4><a href="#">Bibliography</a></h4>
                        
                        
                </div><!-- end entry details -->
         </div>
        <div class="grid_9 omega maincontent"> <!-- Content area -->
                   <div class="grid_9 basics alpha omega">
                       
                       
                       <div class="grid_3 alpha">
                           		<h3>Overview</h3>
                                <p class="ratingName"><strong>Rating: </strong></p>
                                 <p class="rating">
                                        <input name="star1" type="radio" class="star"/> 
                                        <input name="star1" type="radio" class="star"/> 
                                        <input name="star1" type="radio" class="star"/> 
                                        <input name="star1" type="radio" class="star"/> 
                                        <input name="star1" type="radio" class="star"/>
                                 </p>
                                 <div class="clear"></div>
                                 <p><strong>Name:</strong> <?=$theatre->theatre_name;?></p>
                                 <p><strong>Type:</strong> <?=$theatre->period_rep;?></p>
                                 <p><strong>Country:</strong> <?=$theatre->country_name;?></p>
                                 <p><strong>Region:</strong> <?=$theatre->region;?></p>
                                 <p><strong>City:</strong> <?=$theatre->city;?></p>
                                
                        </div>     
                        <div class="grid_6 omega">
                     			<h3>Description</h3>
                                <div id="noStyle"><?=$theatre->brief_description;?></div>
                        </div>  
                        
                   </div>
                   
                   <div class="grid_9 alpha omega tabsContainer">
                   		<ul class="tabs">
                            <li><a href="#stage">Stage</a></li>
							<li><a href="#exterior">Exterior</a></li>
                            <li><a href="#auditorium">Auditorium</a></li>
                            <li><a href="#groundplan">Ground plan</a></li>
                            <li><a href="#section">Section</a></li>
							<li><a href="#otherimages">Other images</a></li>
                            <li><a href="#video">Video</a></li>
                            <li><a href="#map">Map</a></li>
                            <li><a href="#campmodel">CAMP model</a></li>
                        </ul>
                        
                        <div class="tab_container">
                            <div id="stage" class="tab_content">
                               <img src="<?= base_url();?>images/stage.jpg" alt="stage" />
                            </div>
							<div id="exterior" class="tab_content">
                                <img src="<?= base_url();?>images/featured1.jpg" alt="exterior" />
                            </div>
                            <div id="auditorium" class="tab_content">
                               <img src="<?= base_url();?>images/auditorium.jpg" alt="auditorium" />
                            </div>
                          
                            <div id="groundplan" class="tab_content">
                               <img src="<?= base_url();?>images/groundplan.gif" alt="groundplan" />
                            </div>
                            <div id="section" class="tab_content">
                               <img src="<?= base_url();?>images/section.gif" alt="section" />
                            </div>
							<div id="otherimages" class="tab_content">
                               <!--Content-->
                            </div>
                            <div id="video" class="tab_content">
                               <object width="640" height="505"><param name="movie" value="http://www.youtube.com/v/ZYLJF34fi5o&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/ZYLJF34fi5o&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="505"></embed></object>
                            </div>
                            <div id="map" class="tab_content">
                               <iframe width="660" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=palmyra,+syria&amp;sll=37.860975,-78.263337&amp;sspn=0.019618,0.038581&amp;ie=UTF8&amp;hq=&amp;hnear=Palmyra,+Syria&amp;ll=34.547287,38.279114&amp;spn=0.339337,0.823975&amp;z=9&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=palmyra,+syria&amp;sll=37.860975,-78.263337&amp;sspn=0.019618,0.038581&amp;ie=UTF8&amp;hq=&amp;hnear=Palmyra,+Syria&amp;ll=34.547287,38.279114&amp;spn=0.339337,0.823975&amp;z=9&amp;iwloc=A" style="color:#ab2023;text-align:left" target="blank">View larger map</a></small>
                            </div>
                            <div id="campmodel" class="tab_content">
                               <!--Content-->
                            </div>
                        </div>
                   </div>
                   
        </div>
        
       </div><!-- end featuredarea -->
      
       <div class="grid_12 editor">
       		<div class="grid_8 alpha qualityControl">
                  <div class="leftpadding">  
                    <h3>Quality Control</h3>
                    <p class="entryTypeTitle">Entry type:</p>
                    <ul class="entryType">
                        <li><input type="checkbox" />empty</li>
                        <li><input type="checkbox" />1st draft</li>
                        <li><input type="checkbox" />edited draft</li>
                        <li><input type="checkbox" />approved entry</li>
                    </ul>
                    <p class="editorNotesTitle">Notes for later inclusion into narrative:</p>
                    <input type="text" class="editorNotes" />
                    <button type="button">Save</button>
                 </div>
            </div>
            
            <div class="grid_4 omega">
                 <div class="rightpadding"> 
                    <p>Version history (click to edit)</p>
                    <table>
                        <tr><td><a href="#">1st author: XX</a></td></tr>
                        <tr><td><a href="#">1st author: XX</a></td></tr>
                        <tr><td><a href="#">vetted by: XX</a></td></tr>
                        <tr><td><a href="#">board approval date: XX</a></td></tr>
                    </table>
                  </div>
            </div>
       </div>
     
       <!-- Footer -->
       <div class="grid_12 footer">   
            <ul>
            	<li class="grid_2 alpha nav_0"><h4>Contact</h4>
                	   <p>Frank Hildy<br />
						Department of Theatre<br />
						University of Maryland<br />
						College Park, Maryland 20742-1625</p>
                        <p>E-mail frank [at] theatrefinder [dot] com</p>
                </li>
                <li class="grid_2 nav_1"><h4>Home</h4>
                	<ul>
                    	<li><a href="index.html">Featured theatres</a></li>
						<li><a href="index.html">Recently updated theatres</a></li>
						<li><a href="index.html">About Theatre Finder</a></li>
                    </ul>
                </li>
                <li class="grid_2 nav_2"><h4>Location</h4>
                	<ul>
                    	<li><a href="location.html">City</a></li>
						<li><a href="location.html">Country</a></li>
						<li><a href="location.html">Region</a></li>
                    </ul>
                </li>
                <li class="grid_2 nav_3"><h4>Theatre type</h4>
                	<ul>
                    	<li><a href="type.html">Proscenium stage</a></li>
						<li><a href="type.html">Thrust theatre</a></li>
						<li><a href="type.html">End Stage</a></li>
                        <li><a href="type.html">Flexible theatre</a></li>
                        <li><a href="type.html">Profile Theatres</a></li>
                        <li><a href="type.html">Profile Theatres</a></li>
                    </ul>
                </li>
                <li class="grid_2 nav_4"><h4>Period</h4>
                	<ul>
                    	<li><a href="period.html">Minoan</a></li>
						<li><a href="period.html">Greek</a></li>
						<li><a href="period.html">Hellenistic</a></li>
                        <li><a href="period.html">Greco-Roman</a></li>
                        <li><a href="period.html">Roman</a></li>
                        <li><a href="period.html">Medieval</a></li>
                        <li><a href="period.html">Renaissance</a></li>
                        <li><a href="period.html">Baroque</a></li>
                    </ul>
                </li>
                <li class="grid_2 omega nav_5"><h4>Researchers</h4>
                	<ul>
                    	<li><a href="researchers.html">Peer-editing guidelines</a></li>
						<li><a href="researchers.html">CAMP instructions</a></li>
						<li><a href="researchers.html">Create a CAMP model</a></li>
                        <li><a href="researchers.html">Edit a theatre entry</a></li>
                    </ul>
                </li>
 			</ul>
       </div> 
       <div class="grid_12 copyright">	
       		<p>&copy; 2009 Frank Hildy; Branding and website design by <a href="http://mith.info" target="_blank">MITH</a>. All right reserved.</p>
       </div>
</div>

<script type="text/javascript"> Cufon.now(); </script>

</body>
</html>
