<div class="grid_12 breadcrumbs"><p>
		<a href="<?php echo  base_url();?>theatres#<?php echo $theatre['theatre_id'];?>">
			&lt;&lt; &nbsp;back to list</a>
	</p>
</div>
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName"><h1><?php echo $theatre['theatre_name'];?></h1>
	</div>
	<?php echo $form_open;?>
	<?php echo form_hidden('idData',$this->uri->segment(3));?>
	<?php echo validation_errors(); ?>
	
	<div class="grid_3 alpha sidebar">
		<div class="entryDetails">            
			<h4><a href="<?php echo  base_url();?>theatres/edit_visitor_form/<?php echo $curr_theatre_ref;?>">Visitor information</a></h4>
				<ul>
					<li>Overview</li>
					<li>Basic description and significance</li>
					<li>Visiting this theatre</li>
					<li>Related sites nearby</li>
					<li>GPS Coordinates</li>
				</ul>
			<h4><a href="<?php echo  base_url();?>theatres/edit_scholarly_form/<?php echo $curr_theatre_ref;?>">Scholarly details</a></h4>
				<ul>
					<li>General history</li>
					<li>Architectural history</li>
					<li>Description of the current theatre</li>
					<li>Measurements and technical details</li>
				</ul>
			<h4 class="active"><a href="<?php echo  base_url();?>theatres/edit_biblio_form/<?php echo $curr_theatre_ref;?>">Bibliography</a></h4>
			<h4><a href="#">Entry author/editors</a></h4>

	</div><!-- end entry details -->
</div>
<div class="grid_9 omega maincontent"> <!-- Content area -->
	<div class="grid_9 basics alpha omega">
		<p><input type="submit" value="Update" class="submit" /></p>
		<h3>Bibliography</h3>
		<div id="noStyle"><?php echo $biblio;?> 
		<?php echo display_ckeditor($ckeditor_biblio);?>
		</div>
	</div>
                   
    <div class="grid_9 alpha omega tabsContainer">
        <ul class="tabs">
            <li><a href="#stage">Stage</a></li>
            <li><a href="#auditorium">Auditorium</a></li>
			<li><a href="#exterior">Exterior</a></li>
            <li><a href="#groundplan">Ground plan</a></li>
            <li><a href="#section">Section</a></li>
            <li><a href="#video">Video</a></li>
            <li><a href="#map">Map</a></li>
            <li><a href="#campmodel">CAMP model</a></li>
            <li><a href="#otherimages">Other images</a></li>
        </ul>
        <div class="tab_container">
            <div id="exterior" class="tab_content">
                <img src="images/featured1.jpg" alt="exterior" />
            </div>
            <div id="auditorium" class="tab_content">
                <img src="images/auditorium.jpg" alt="auditorium" />
            </div>
            <div id="stage" class="tab_content">
                <img src="images/stage.jpg" alt="stage" />
            </div>
            <div id="groundplan" class="tab_content">
                <img src="images/groundplan.gif" alt="groundplan" />
            </div>
            <div id="section" class="tab_content">
                <img src="images/section.gif" alt="section" />
            </div>
            <div id="video" class="tab_content">
                <object width="640" height="505">
                    <param name="movie" value="http://www.youtube.com/v/ZYLJF34fi5o&hl=en_US&fs=1&">
                    </param><param name="allowFullScreen" value="true">
                    </param><param name="allowscriptaccess" value="always">
                    </param>
                    <embed src="http://www.youtube.com/v/ZYLJF34fi5o&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="505">
                    </embed>
                </object>
            </div>
            <div id="map" class="tab_content">
                <iframe width="660" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=palmyra,+syria&amp;sll=37.860975,-78.263337&amp;sspn=0.019618,0.038581&amp;ie=UTF8&amp;hq=&amp;hnear=Palmyra,+Syria&amp;ll=34.547287,38.279114&amp;spn=0.339337,0.823975&amp;z=9&amp;iwloc=A&amp;output=embed">
                </iframe>
                <br/>
                <small>
                    <a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=palmyra,+syria&amp;sll=37.860975,-78.263337&amp;sspn=0.019618,0.038581&amp;ie=UTF8&amp;hq=&amp;hnear=Palmyra,+Syria&amp;ll=34.547287,38.279114&amp;spn=0.339337,0.823975&amp;z=9&amp;iwloc=A" style="color:#ab2023;text-align:left" target="blank">View larger map</a>
                </small>
            </div>
            <div id="campmodel" class="tab_content">
                <!--Content-->
            </div>
            <div id="otherimages" class="tab_content">
                <!--Content-->
            </div>
        </div>
    </div>
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