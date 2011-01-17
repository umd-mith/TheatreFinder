<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Theatre Finder | Home</title>
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
	Cufon.replace('h2');
	Cufon.replace('h3');
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

</head>

<body id="home">

<div class="container_12 clearfix">
    	<!-- Navigation area -->
        <div class="grid_12 nav">   
            <ul>
	         	<li class="grid_2 alpha logo"><h1><a href="index.html">Theatre Finder</a></h1></li>
                <li class="grid_2 nav_1 current"><h2><a href="index.html">home</a></h2>
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
            <!-- <div class="grid_12 alpha omega breadcrumbs"><p>Search > Theatre</p></div> -->
       </div>
       
       
       <!-- Featured theatres -->
       <div class="grid_12 featuredarea">   
            <div class="grid_8 alpha featuredimage">
            	<h1>Featured theatres</h1>
                <img src="images/featured2.jpg" alt="featured theatre 1" />
            </div>
            <div class="grid_4 omega featuredsidebar">
                    <div class="grid_4 alpha omega theatre active">
                    <h3>Featured Theatre One</h3><p>This is some text about the theatre. This is some more text describing this theatre. <a href="entry.html">This is a link to the full page of theatre description.</a> <span class="tag"><strong>Tags:</strong> Greece, procenium stage</span></p>
                    </div>   
                    <div class="grid_4 alpha omega theatre">
                    <h3>Featured Theatre Two</h3><p>This is some text about the theatre. This is some more text describing this theatre. <a href="entry.html">This is a link to the full page of theatre description.</a> <span class="tag"><strong>Tags:</strong> Greece, procenium stage</span></p>
                    </div>
                    <div class="grid_4 alpha omega theatre last">
                    <h3>Featured Theatre Three</h3><p>This is some text about the theatre. This is some more text describing this theatre. <a href="entry.html">This is a link to the full page of theatre description.</a>  <span class="tag"><strong> Tags:</strong> Greece, procenium stage</span></p>
                    </div>
            </div>
       </div>
       
       <!-- Content area -->
		<div class="grid_12 maincontent">   
				<div class="grid_8 alpha">
						<h2>Recently updated theatres</h2>
                                                   
                        <div class="grid_2 boxgrid captionfull alpha">	<!-- this is the image -->
                            <img src="images/update1.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull">	<!-- this is the image -->
                            <img src="images/update2.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull">	<!-- this is the image -->
                            <img src="images/update3.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                    
                       <div class="grid_2 boxgrid captionfull omega">	<!-- this is the image -->
                            <img src="images/update4.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull alpha">	<!-- this is the image -->
                            <img src="images/update5.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull">	<!-- this is the image -->
                            <img src="images/update6.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull">	<!-- this is the image -->
                            <img src="images/update7.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
                        
                        <div class="grid_2 boxgrid captionfull omega">	<!-- this is the image -->
                            <img src="images/update8.png" alt="featured theatre 1" />
                                <div class="cover boxcaption"> <!-- this is the hover -->
                                    <h4>Bosra Theatre</h4>
                                    <p>Ancient Roman theatre in Syria <br /><a href="#">View details</a></p>   
                            </div>
                        </div>
				</div>
            
				<div class="grid_4 omega">
                    <h2>Introduction to theatre-finder.org</h2>
                    <p>This Comprehensive World Wide  Digital Archive of Existing Historic  Theatres is a collaboratively edited, peer reviewed, online database of historic theatre architecture from the Minoan “theatrical areas” on the island of Crete, to the last theatre built before in 1815. Recent scholarship has put increasing emphasis on the places of performance, examining the ways in which space can be manipulated to bring performers and their audiences together. A significant component of such work has been the study of theatre architecture.</p>
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
                <li class="grid_2 nav_1 current"><h4>Home</h4>
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
