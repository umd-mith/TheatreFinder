<div class="grid_12 breadcrumbs"><p>
		<a href="<?php echo  base_url();?>theatres#<?php echo $theatre['theatre_id'];?>">&lt;&lt; &nbsp;back to list</a>
	</p>
</div>
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName"><h1><?php echo $theatre['theatre_name'];?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="entryDetails">
                    
			<h4><a href="<?php echo  base_url();?>theatres/entry_visitor_info/<?php echo $curr_theatre_ref;?>">Visitor information</a></h4>
                        	<ul>
								<li>Overview</li>
                            	<li>Basic description and significance</li>
                                <li>Visiting this theatre</li>
                                <li>Related sites nearby</li>
								<li>GPS Coordinates</li>
								<li>Entry Author/Editor</li>
                            </ul>
			<h4 class="active"><a href="<?php echo  base_url();?>theatres/entry_scholarly_details/<?php echo $curr_theatre_ref;?>">Scholarly details</a></h4>
                        	<ul>
                        		<li><a href="#alt_names">Alternative names for this theatre</a></li>
								<li><a href="#alt_names">Alternative names for this theatre's city</a></li>
                            	<li><a href="#gen_history">General history</a></li>
                                <li><a href="#arch_history">Architectural history</a></li>
                                <li><a href="#current_desc">Description of the current theatre</a></li>
                                <li><a href="#measurements">Measurements and technical details</a></li>
								<li><a href="#biblio">Bibliography</a></li>
                            </ul>
		<!--	<h4><a href="<?php echo  base_url();?>theatres/entry_biblio/<?php echo $curr_theatre_ref;?>">Bibliography</a></h4>
			<h4><a href="#">Entry author/editors</a></h4>
		-->
			
	</div><!-- end entry details -->
</div>
<div class="grid_9 omega maincontent"> <!-- Content area -->
                   <div class="grid_9 basics alpha omega">
                   	<a name="alt_names"></a><div class="grid_9 alpha omega"><h3>Alternative names ("aliases")</h3>
                   		<div class="grid_4 alpha">
         					<h4>Alternative names for this theatre</h4>
							<?php foreach($t_aliases as $theatre_alias):?>	
							<div class="grid_3 prefix_1 alpha"><em>- <?php echo $theatre_alias['theatre_alias'];?></em></div>
							<?php endforeach;?>          			
                   		</div>
						<div class="grid_5 omega">
							<h4>Alternative names for this theatre's city</h4>
							<?php foreach($c_aliases as $city_alias):?>	
							<div class="grid_4 prefix_1 omega"><em> - <?php echo $city_alias['city_alias'];?></em></div>
							<?php endforeach;?>  
						</div>
                   	</div>
					<div class="clear"></div>
					<div class="grid_9 basics alpha omega">
                           		<a name="gen_history"></a><h3>General History</h3>							
                                <div id="noStyle"><?php echo $theatre['general_history'];?></div>
                    			<a name="arch_history"></a><h3>Architectural History</h3>
								<h4>Previous Theatres On-Site</h4>
								<div id="noStyle"><?php echo $theatre['previous_theatres_onsite'];?></div>
                                <h4>Alterations, Redecorations, Renovations, Recontructions done on this site</h4>
								<div id="noStyle"><?php echo $theatre['alts_renovs_list'];?></div>
							
								<a name="current_desc"></a><h3>Description of Current Theatre</h3>
                                <div id="noStyle"><?php echo $theatre['desc_current'];?></div>
								
								<a name="measurements"></a><h3>Measurements and Technical Details</h3>
                                <div id="noStyle"><?php echo $theatre['measurements'];?></div>
                        
								<a name="biblio"></a><h3>Bibliography</h3>
								<div id="noStyle"><?php echo $biblio;?></div>
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
				<img src="<?php echo  base_url().$theatre['stage_image'];?>" alt="stage" />
        	</div>
			<div id="exterior" class="tab_content">
				<img src="<?php echo  base_url();?><?php echo $theatre['exterior_image'];?>" alt="exterior" />
			</div>
 			<div id="auditorium" class="tab_content">
				<img src="<?php echo  base_url();?><?php echo $theatre['auditorium_image'];?>" alt="auditorium" />
			</div> 
			<div id="groundplan" class="tab_content">
				<img src="<?php echo  base_url();?><?php echo $theatre['plan_image'];?>" alt="groundplan" />
			</div>
			<div id="section" class="tab_content">
				<img src="<?php echo  base_url();?><?php echo $theatre['section_image'];?>" alt="section" />
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