<div class="container_12 clearfix">
    	<!-- Navigation area -->
        <div class="grid_12 nav">   
            <ul id="nav_header">
	         	<li class="grid_2 alpha logo"><h1><a href="<?php echo base_url();?>main">Theatre Finder</a></h1></li>
                <li class="grid_2 nav_1"><h2><a href="<?php echo base_url();?>main/about">about finder</a></h2>
                	<p>Information on what is covered in this guide</p>
                </li>
                <li class="grid_2 nav_2"><h2><a href="<?php echo base_url();?>main/search">search finder</a></h2>
                	<p>Advanced textual and geographic searches</p>
                </li>
                <li class="grid_2 nav_3"><h2><a href="<?php echo base_url();?>main/join">join finder</a></h2>
                	<p>Create &amp; edit entries, construct CAMP models</p>
                </li>
                <li class="grid_2 nav_4"><h2><a href="<?php echo base_url();?>main/contribute">add to finder</a></h2>
                	<p>Submit your photos or videos to finder</p>
                </li>
                <li class="grid_2 omega nav_5"><h2><a href="<?php echo base_url();?>main/resources">resources</a></h2>
                	<p>Further resources on historic theatres</p>
                </li>
			</ul>
         </div>     
       
       <!-- Search area -->
       <div class="grid_12 searchbar">   
	   <a name="search_bar"></a>
            <div class="grid_8 alpha">
            	<h2>A comprehensive, web-based, world-wide guide to all theatres over 100 years old </h2>
				<a href="#" class="tipsy" original-title="We are in Phase I—Pre-Greek to 1815. Theatres in Phase II (1816-1865) and Phase III (1866-1915) are being listed as they are found but do not have full entries."><span class="note"></span></a>
            </div>
            <div class="grid_4 omega">
            	<form class="search" action="<?php echo base_url();?>theatre_ctrl/search_theatres" method="post" accept-charset="utf-8">	
                    <select id="search_menu" name="search_menu">
                        <option value="all">All</option>
						<option value="period_rep">Period</option>
                        <option value="country_name">Country</option>
                        <option value="city">City</option>
						<option value="date_ce">Date (CE)</option>
						<option value="date_bce">Date (BCE)</option>
						<option value="date_house">House Date</option>
                        <option value="theatre_name">Name</option>
                    </select>
                    <input type="text" size="16" name="search_text" />
                    <input type="submit" name="submit" value="Search" />
                </form>
            </div>
            <!-- <div class="grid_12 alpha omega breadcrumbs"><p>Search > Theatre</p></div> -->
       </div>