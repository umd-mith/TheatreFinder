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
			<h4 class="active"><a href="<?php echo  base_url();?>theatres/edit_scholarly_form/<?php echo $curr_theatre_ref;?>">Scholarly details</a></h4>
				<ul>
					<li><a href="#gen_history">General history</a></li>
                    <li><a href="#arch_history">Architectural history</a></li>
                    <li><a href="#current_desc">Description of the current theatre</a></li>
                    <li><a href="#measuremnts">Measurements and technical details</a></li>
					<li><a href="#biblio_list">Bibliography</a></li>
					<li><a href="#authors">Entry Author(s)/Editor(s)</a></li>
				</ul>
			<!--<h4><a href="<?php echo  base_url();?>theatres/edit_biblio_form/<?php echo $curr_theatre_ref;?>">Bibliography</a></h4>
			<h4><a href="#">Entry author/editors</a></h4>-->

		</div><!-- end entry details -->
	</div>
<div class="grid_9 omega maincontent"> <!-- Content area -->
	<div class="grid_9 basics alpha omega">
		<p><input type="submit" value="Update" class="submit" /></p>
		<h3><a name="gen_history">General History</a></h3>
		<div id="noStyle"><?php echo $general_history;?>
		<?php echo display_ckeditor($ckeditor_general_history); ?>
		</div>
		<h3><a name="arch_history">Architectural History</a></h3>
		<h4>Previous Theatres On-Site</h4>
		<div id="noStyle"><?php echo $previous_theatres_onsite;?>
		<?php echo display_ckeditor($ckeditor_prev_theatres); ?>
		</div>
		<p></p>
		<h4>Alterations, Redecorations, Renovations, Recontructions done on this site</h4>
		<div id="noStyle"><?php echo $alts_renovs_list;?>
		<?php echo display_ckeditor($ckeditor_alts_renovs); ?>
		</div>
		<h3><a name="current_desc">Description of Current Theatre</a></h3>
		<div id="noStyle"><?php echo $desc_current;?>
		<?php echo display_ckeditor($ckeditor_desc_current); ?>
		</div>
		<h3><a name="measuremnts">Measurements and Technical Details</a></h3>
		<div id="noStyle"><?php echo $measurements;?>
		<?php echo display_ckeditor($ckeditor_measurements);?>
		</div>
		<h3><a name="biblio_list">Bibliography</a></h3>
		<div id="noStyle"><?php echo $biblio;?>
		<?php echo display_ckeditor($ckeditor_biblio);?>
		</div>
	</div>
	<p><input type="submit" value="Update" class="submit" /></p>
</div> <!-- end Content area -->
</div> <!-- end Featured Areas -->
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