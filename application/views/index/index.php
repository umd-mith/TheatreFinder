<!-- Content area -->
<div class="grid_12 maincontent">
    <div class="grid_6 alpha featured_text"><a id="about"></a>
        <h2 class="blockheader-font">Theatre Finder is a comprehensive, web-based, world-wide guide to theatres over 100 years old</h2>
		 <p>Theatre Finder is a guide to the historic theatres that exist around the world. We want you to visit them, experience what it is like to stand inside them, and to take an interest in their preservation. Our goal is to provide you with the most accurate information possible about each theatre in order to enhance your understanding of them and as an aid to those who wish to research them further.</p>
  	<p>For more details visit the <a href="main/about">about finder</a> page. To apply to join the project, visit the <a href="main/join">join finder</a> section.</p>
    </div>

    <div class="grid_6 omega"><a id="updated"></a>
        <h2>Recently Updated Theatre Entries</h2>

		<div class="grid_2 boxgrid captionfull alpha">
		<!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[0]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[0]['country_name']; ?></h4>
                <p>
                   <?php echo $recent_theatres[0]['city']; ?><br>			   
				   <?php echo $recent_theatres[0]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[0]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
       <div class="grid_2 boxgrid captionfull">
            <!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[1]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[1]['country_name']; ?></h4>
                <p>
                   <?php echo $recent_theatres[1]['city']; ?> <br>
				   <?php echo $recent_theatres[1]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[1]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
        <div class="grid_2 boxgrid captionfull omega">
            <!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[2]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[2]['country_name']; ?></h4>
                <p>
                 <?php echo $recent_theatres[2]['city']; ?> <br>
				  <?php echo $recent_theatres[2]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[2]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
		<div class="grid_2 boxgrid captionfull alpha">
		<!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[3]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[3]['country_name']; ?></h4>
                <p>
                   <?php echo $recent_theatres[3]['city']; ?> <br>			   
				   <?php echo $recent_theatres[3]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[3]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
       <div class="grid_2 boxgrid captionfull">
            <!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[4]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[4]['country_name']; ?></h4>
                <p>
                   <?php echo $recent_theatres[4]['city']; ?><br>
				   <?php echo $recent_theatres[4]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[4]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
        <div class="grid_2 boxgrid captionfull omega">
            <!-- this is the image --><img src="<?php echo base_url();?><?php echo $recent_theatres[5]['thumbnail'];?>" alt="featured theatre 1" />
            <div class="cover boxcaption">
                <!-- this is the hover --><h4><?php echo $recent_theatres[5]['country_name']; ?></h4>
                <p>
                 <?php echo $recent_theatres[5]['city']; ?><br>
				  <?php echo $recent_theatres[5]['date_range']; ?>
                    <br/>
                    <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $recent_theatres[5]['id'];?>_top">View details</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Featured theatres -->
<div class="grid_12 featuredarea">
    <div class="grid_8 alpha featuredimage">
        <h1>Featured theatres</h1>
        <img src="<?php echo base_url();?><?php echo $featured_1['featured_img'];?>" alt="featured theatre 1" width="620" height="464" id="featuredimage" />
    </div>
    <div class="grid_4 omega featuredsidebar" >
        <div class="grid_4 alpha omega theatre active" id="featured_one">
            <h3><?php echo $featured_1['theatre_name']; ?></h3>
            <p>
			<?php echo $featured_1['period_rep']; ?> garden theatre, <?php echo $featured_1['date_range'];?>,
			<?php echo $featured_1['city'];?>, <?php echo $featured_1['country_name']." (".$featured_1['country_digraph']."). "; ?>
			<?php echo $featured_1['featured_text'];?>
			<a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $featured_1['id'];?>_top">
                	View complete theatre description.</a>
		   </p>
        </div>
        <div class="grid_4 alpha omega theatre " id="featured_two">
            <h3><?php echo $featured_2['theatre_name']; ?></h3>
            <p>
                <?php echo $featured_2['period_rep']; ?> theatre, <?php echo $featured_2['date_range'];?>, 
			<?php echo $featured_2['city'];?>, <?php echo $featured_2['country_name']." (".$featured_2['country_digraph']."). "; ?>
			<?php echo $featured_2['featured_text'];?> 
			 <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $featured_2['id'];?>_top">
			 	View complete theatre description.</a>
            </p>
        </div>
        <div class="grid_4 alpha omega theatre last" id="featured_three">
            <h3><?php echo $featured_3['theatre_name']; ?></h3>
            <p>
            <?php echo $featured_3['period_rep']; ?> theatre, circa <?php echo $featured_3['date_range'];?>, 
			<?php echo $featured_3['city'];?>, <?php echo $featured_3['country_name']." (".$featured_3['country_digraph']."). "; ?>
			<?php echo $featured_3['featured_text'];?>
			 <a href="<?php echo base_url().$view_controller;?>/entry_visitor_info/<?php echo $featured_3['id'];?>_top">
			 	View complete theatre description.</a>
            </p>
        </div>
    </div>
</div>