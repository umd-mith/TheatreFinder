<div class="grid_12 breadcrumbs"><p>
		<a href="<?php echo  base_url();?>theatres#<?php echo $theatre['id'];?>">&lt;&lt; &nbsp;back to list</a>
	</p>
</div>
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName"><h1><?php echo $theatre['theatre_name'];?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="entryDetails">
                    
			<h4 class="active"><a href="#">Visitor information</a></h4>
                        	<ul>
								<li><a href="#overview">
									Overview</a></li>
                            	<li><a href="#basic_desc">Basic description and significance</a></li>
                                <li><a href="#visiting">Visiting this theatre</a></li>
                                <li><a href="#rel_sites">Related sites nearby</a></li>
								<li><a href="#gps">GPS Coordinates</a></li>
								<li><a href="#entry_author">Entry Author/Editor</a></li>
								<?php
								  if(array_key_exists('Edit', $theatre)) {
									?><li><?php echo $theatre['Edit']; ?></li><?php
								  }
								?>
                            </ul>
			<h4><a href="<?php echo  base_url();?>theatres/entry_scholarly_details/<?php echo $curr_theatre_ref;?>">Scholarly details</a></h4>
                        	<ul>
                        		<li>Alternative names for this theatre</li>
								<li>Alternative names for this theatre's city</li>
                            	<li>General history</li>
                                <li>Architectural history</li>
                                <li>Description of the current theatre</li>
                                <li>Measurements and technical details</li>
								<li>Bibliography</li>
                            </ul>
			<!-- <h4><a href="<?php echo  base_url();?>theatres/entry_biblio/<?php echo $curr_theatre_ref;?>">Bibliography</a></h4>
			<h4><a href="#">Entry author/editors</a></h4>
			-->
	</div><!-- end entry details -->
</div>
<div class="grid_9 omega maincontent"> <!-- Content area -->
	<div class="grid_9 basics alpha omega">                      
		<div class="grid_3 alpha">
			<a name="overview"></a><h3>Overview</h3>
			<div class="grid_1 alpha"><p class="ratingName"><strong>Rating: </strong></p></div>
			<input type="hidden" id="db_star_rating" name="db_star_rating" value="<?php echo $theatre['rating'];?>"/>
			<p class="rating"><div class="grid_2 alpha">
   	         <input name="star1" type="radio" class="star" value="1" />
			 <input name="star1" type="radio" class="star" value="2" />
			 <input name="star1" type="radio" class="star" value="3" />
			 <input name="star1" type="radio" class="star" value="4" />
			 <input name="star1" type="radio" class="star" value="5" />
			 </div></p>
		<div class="clear"></div>
			<p><strong>Country:</strong> <?php echo $theatre['country_name']." (".$theatre['country_digraph'].")";?></p>
			<p><strong>City:</strong> <?php echo $theatre['city'];?></p>                        
			<p><strong>Region:</strong> <?php echo $theatre['region'];?></p>
			<p><strong>Type:</strong> <?php echo $theatre['period_rep'];?></p>
			<p><strong>Date:</strong> <?php echo $date_string?></p>
			<p><strong>House Date:</strong> <?php echo $theatre['auditorium_date'];?>
			<br>(Auditorium Date)</p>
			<p><strong><a name="gps">GPS Coordinates:</a></strong>
			<br>Lat: <?php echo $theatre['lat_dms'];?><br>Long: <?php echo $theatre['lng_dms'];?></p>
			<p><strong>Listed by:</strong> <?php echo $theatre['entry_first_lister'];?>
			<br><?php echo "First listed on ".$theatre['entry_date'];?></p>

		</div>     
        <div class="grid_6 omega">
        	<a name="entry_status"></a><h3 class="status">Entry Status:</h3>
			<p class="<?php echo $theatre['status_css_class'];?>"><?php echo $theatre['entry_status'];?></p>
			
			<a name="basic_desc"></a><h3>Basic Description and Significance</h3>
			<div id="noStyle"><?php echo $theatre['basic_description'];?></div>
			<a name="visiting"></a><h3>Visiting this theatre</h3>
			<div id="noStyle"><?php echo $theatre['visiting_info'];?></div>
			<a name="rel_sites"></a><h3>Related sites nearby</h3>
			<div id="noStyle"><?php echo $theatre['related_sites'];?></div>
			<a name="entry_author"></a><h3>Entry By: </h3>
			<div id="noStyle"><?php echo $theatre['entry_author'].", Last Updated on ".$theatre['last_updated'];?></div>
			<h3>Entry Edited By: </h3>
			<div id="noStyle"><?php echo $theatre['entry_editor'].", Last Updated on ".$theatre['last_updated'];?></div>
        	
		</div>                  
	</div>
	<div class="grid_9 alpha omega tabsContainer">
		<ul class="tabs">
			<li><a href="#stage">Stage</a></li>
            <li><a href="#auditorium">Auditorium</a></li>
			<li><a href="#exterior">Exterior</a></li>
			<li><a href="#groundplan">Ground plan</a></li>
			<li><a href="#section">Section</a></li>
			<li><a href="#otherimages">Other images</a></li>
			<li><a href="#video">Video</a></li>
			<li><a href="#map">Map</a></li>
			<li><a href="#campmodel">CAMP model</a></li>
		</ul>
		<div class="tab_container">
			<div id="stage" class="tab_content">
				<?php if($theatre['needs_stage_image'] || $can_upload_images): ?>
					<a href="<?php echo base_url(); ?>upload/index/<?php echo $theatre['id']?>/stage">
				<?php endif ?>
				<img src="<?php echo  base_url().$theatre['stage_image'];?>" alt="stage" />
				<?php if($theatre['needs_stage_image'] || $can_upload_images): ?>
					</a>
				<?php endif ?>
        	</div>
			<div id="exterior" class="tab_content">
				<?php if($theatre['needs_exterior_image'] || $can_upload_images): ?>
					<a href="<?php echo base_url(); ?>upload/index/<?php echo $theatre['id']?>/exterior">
				<?php endif ?>
				<img src="<?php echo  base_url();?><?php echo $theatre['exterior_image'];?>" alt="exterior" />
				<?php if ($theatre['needs_exterior_image'] || $can_upload_images): ?>
					</a>
				<?php endif ?>
			</div>
 			<div id="auditorium" class="tab_content">
				<?php if ($theatre['needs_auditorium_image'] || $can_upload_images): ?>
					<a href="<?php echo base_url(); ?>upload/index/<?php echo $theatre['id']?>/auditorium">
				<?php endif ?>
				<img src="<?php echo  base_url();?><?php echo $theatre['auditorium_image'];?>" alt="auditorium" />
				<?php if ($theatre['needs_auditorium_image'] || $can_upload_images): ?>
					</a>
				<?php endif ?>
			</div> 
			<div id="groundplan" class="tab_content">
				<?php if ($theatre['needs_plan_image'] || $can_upload_images): ?>
					<a href="<?php echo base_url(); ?>upload/index/<?php echo $theatre['id']?>/groundplan">
				<?php endif ?>
				<img src="<?php echo  base_url();?><?php echo $theatre['plan_image'];?>" alt="groundplan" />
				<?php if ($theatre['needs_plan_image'] || $can_upload_images): ?>
					</a>
				<?php endif ?>
			</div>
			<div id="section" class="tab_content">
				<?php if ($theatre['needs_section_image'] || $can_upload_images): ?>
					<a href="<?php echo base_url(); ?>upload/index/<?php echo $theatre['id']?>/section">
				<?php endif ?>
				<img src="<?php echo  base_url();?><?php echo $theatre['section_image'];?>" alt="section" />
				<?php if ($theatre['needs_section_image'] || $can_upload_images): ?>
					</a>
				<?php endif ?>
			</div>
			<div id="otherimages" class="tab_content">
			<!--Content-->
			</div>
			<div id="video" class="tab_content">
				<object width="640" height="505">
					<param name="movie" value="<?php echo $theatre['video_link'];?>"></param>
					<param name="allowFullScreen" value="true"></param>
					<param name="allowscriptaccess" value="always"></param>
					<embed src="<?php echo $theatre['video_link'];?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="505">
					</embed>
				</object>
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
</div>
<!-- end featuredarea -->
<!--<div class="grid_12 editor">
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
</div>-->