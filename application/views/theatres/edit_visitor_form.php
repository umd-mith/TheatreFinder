<div class="grid_12 breadcrumbs">
	<p><a href="<?php echo  base_url();?>theatres#<?php echo $theatre['id'];?>">&lt;&lt; &nbsp;back to list</a>
	</p>
</div>
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName"><h1><?php echo $heading;?></h1>
	</div>
	<!-- several hidden elements in this form: idData, theatre_id, city_id -->
	<?php echo $form_open;?>
	<?php echo form_hidden('idData',$this->uri->segment(3));?>
	<?php echo validation_errors(); ?>
	<input type="hidden" id="theatre_id" name="theatre_id" value="<?php echo $theatre_id;?>" />
	<input type="hidden" id="city_id" name="city_id" value="<?php echo $city_id;?>" />
	<div class="clear"></div>
	<div class="grid_3 alpha sidebar">
		<div class="entryDetails">
		
			<h4 class="active"><a href="<?php echo  base_url();?>theatres/edit_visitor_form/<?php echo $this->uri->segment(3);?>">Visitor information</a></h4>
                        	<ul>
								<li><a href="#overview">Overview</a></li>
                            	<li><a href="#basic_desc">Basic description and significance</a></li>
                                <li><a href="#visiting">Visiting this theatre</a></li>
                                <li><a href="#rel_sites">Related sites nearby</a></li>
								<li><a href="#gps">GPS Coordinates</a></li>
                            </ul>
			<h4><a href="<?php echo  base_url();?>theatres/edit_scholarly_form/<?php echo $this->uri->segment(3);?>">Scholarly details</a></h4>
                        	<ul>
                            	<li>General history</li>
                                <li>Architectural history</li>
                                <li>Description of the current theatre</li>
                                <li>Measurements and technical details</li>
								<li>Bibliography</li>
								<li>Entry Author(s)/Editor(s)</li>
                            </ul>
		<!--   <h4><a href="<?php echo  base_url();?>theatres/edit_biblio_form/<?php echo $this->uri->segment(3);?>">Bibliography</a></h4>
		   <h4><a href="#">Entry author/editors</a></h4>
		-->
			
	</div><!-- end entry details -->
</div>

<div class="grid_9 omega maincontent"> <!-- Content area -->

<div class="grid_9 basics alpha">                      
		<div class="grid_9 alpha omega">
			<p><input type="submit" value="Update" class="submit" /></p>
			<h3><a name="overview">Overview</a></h3>
			<div class="grid_1 alpha"><p class="ratingName"><strong>Rating: </strong></p></div>
            <input type="hidden" id="db_star_rating" name="db_star_rating" value="<?php echo $theatre['rating'];?>"/>
			<p class="rating"><div class="grid_2 alpha">
   	         <input name="star1" type="radio" class="star" value="1" />
			 <input name="star1" type="radio" class="star" value="2" />
			 <input name="star1" type="radio" class="star" value="3" />
			 <input name="star1" type="radio" class="star" value="4" />
			 <input name="star1" type="radio" class="star" value="5" />
			 </div>
			 <div class="grid_6 omega">
             <em>Edit this theatre's current rating (<strong><?php echo $theatre['rating'];?></strong> stars) </em>
			</div></p>
		<div class="clear"></div>
		<hr>
		<div class="grid_9 alpha omega">
			<div class="grid_3 alpha"><p><strong>Author:</strong> <?php echo $authorInput;?></p></div>
			<div class="grid_3 alpha"><p><strong>Editor:</strong> <?php echo $editorInput;?></p></div>
			<div class="grid_3 omega"><strong>Status:</strong> <?php echo $status_menu;?></div>
		</div>
		<div class="clear"></div>
		<hr>
		<div class="grid_9 alpha omega">
			<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><p><strong>*Name:</strong></p></div>
			<div class="grid_3 alpha"><p><?php echo $nameInput;?></p></div>
			<div class="grid_5 omega"><?php echo $theatre_alias_chkBox;?>
				<label for="theatre_aliasCB"><strong>Add Theatre Alias</strong></label>
				</input>
			</div>
			<div id="theatreAliasDiv_1" style="margin-bottom:4px" class="theatre_aliases grid_5 prefix_4 omega">
				<label for="theatreAlias_1">Alias 1:</label> 
				<input type="text" name="theatre_aliases[]" id="theatreAlias_1" size="16" maxlength="64"/>
				<img id="add_btn" class="icon" alt="Add button" title="add an alias" src="<?php echo  base_url();?>/images/icon_add.png">
				<img id="del_btn" class="icon" alt="Delete button" title="Remove this alias" src="<?php echo  base_url();?>/images/icon_delete.png">
			</div>
			<div class="clear" id="last"></div>
			</div> <!--end Theatre Name alias section-->
			<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><p><strong>*Country:</strong></p></div>
			<div class="grid_3 alpha"><p><?php echo $countryInput;?></p></div>
			<div class="grid_5 omega"><!-- just added to line up input fields correctly --></div>
			</div>
			<div class="clear"></div>
			<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><p><strong>Region:</strong></p></div>
			<div class="grid_3 alpha"><p><?php echo $regionInput;?></p></div>
			<div class="grid_5 omega"><!-- just added to line up input fields correctly --></div>
			</div>
			<div class="clear"></div>
			<div class="grid_9 alpha omega">
				<div class="grid_1 alpha"><p><strong>*City:</strong></p></div>
				<div class="grid_3 alpha"><p><?php echo $cityInput;?></p></div>
				<div class="grid_5 omega"><?php echo $cAliasChkBox;?>
					<label for="cAliasCB"><strong>Add City Alias</strong></label>
					</input>
				</div>
			<div id="cityAliasDiv_1" style="margin-bottom:4px" class="cityAliases grid_5 prefix_4 omega">
					<label for="cityAliasName_1">Alias 1:</label> 
					<input type="text" name="cAliases[]" id="cityAliasName_1" size="16" maxlength="64"/>
					<img id="add_btn" class="icon" alt="Add button" title="add an alias" src="<?php echo  base_url();?>/images/icon_add.png">
					<img id="del_btn" class="icon" alt="Delete button" title="Remove this alias" src="<?php echo  base_url();?>/images/icon_delete.png">
			</div>
			<div class="clear" id="last"></div>
			</div>
		<div class="clear"></div>
		<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><p><strong>Period:</strong></p></div>
			<div class="grid_5 alpha"><p><?php echo $periodMenu;?></p></div>
			<div class="grid_3 omega"></div>
			<div class="clear"></div>
			<div class="grid_1 alpha" ><p><strong>Type:</strong></p></div>
			<div class="grid_5 alpha" id="type_wrapper"><p><?php echo $sub_type;?></p></div>
			<div class="grid_3 omega"></div>
		</div>
		<div class="clear"></div>
		<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><p><strong>Range (start)</strong></p></div>
			<div class="grid_4 alpha">
				<p><?php echo $est_earliest;?>CE<?php echo $earliest_ce;?> BCE <?php echo $earliest_bce;?></p>
		    </div>
			<div class="grid_4 omega"></div>
			<div class="clear"></div>
			<div class="grid_1 alpha"><p><strong>Range (end)</strong></p></div> 
			<div class="grid_4 alpha">
				<p><?php echo $est_latest;?>CE <?php echo $latest_ce;?> BCE <?php echo $latest_bce;?></p>
			</div>
			<div class="grid_4 omega"></div>
			<div class="clear"></div>
			<div class="grid_1 alpha"><p><strong>Auditorium Date: </strong</p></div>
			<div class="grid_4 alpha">
				<?php echo $auditorium_date;?>
			</div>
			<div class="grid_4 omega"></div>
			<div class="clear"></div>
			<div class="grid_1 alpha"><p><strong>Listed by:</strong></p></div>
			<div class="grid_3 alpha"><?php echo $entry_first_lister;?></div>
			<div class="grid_5 omega"></div>
		</div>
		<hr>
		<div class="grid_9 alpha omega"><strong><p>GPS Coordinates</p></strong></div>
		<div class="clear"></div><p>
		<div class="grid_9 alpha omega">
			<div class="grid_1 alpha"><em>Latitude</em></div>
			<div class="grid_3 alpha">
				<?php echo $lat_degrees;?><strong>&deg;</strong>
				<?php echo $lat_mins;?><strong>&rsquo;</strong>
				<?php echo $lat_secs;?><strong>&rdquo;</strong>
				<strong>N</strong><?php echo $north_radio;?>
				<strong>S</strong><?php echo $south_radio;?>
			</div>
			<div class="grid_1 alpha"><em>Longitude</em></div>
			<div class="grid_4 omega">
				<?php echo $lng_degrees;?><strong>&deg;</strong> 
				<?php echo $lng_mins;?><strong>&rsquo;</strong> 
				<?php echo $lng_secs;?><strong>&rdquo;</strong> 
				<strong>E</strong><?php echo $east_radio;?>
				<strong>W</strong><?php echo $west_radio;?>
			</div>
		</div>
		<div class="clear"></div>
		</p>
		<hr>
	</div>
	<div class="clear"></div>    
	<div class="grid_9 basics alpha omega">
		<h3>Running Notes (not viewable in Public Entry)</h3>
		<div id="noStyle"><?php echo $running_notes;?> 
		<?php echo display_ckeditor($ckeditor_notes);?>
		</div>  
        <h3><a name="basic_desc">Basic Description and Significance</a></h3>
		<div id="noStyle"><?php echo $basic_description;?>
			<?php echo display_ckeditor($ckeditor_basic); ?>
		</div>
	</div>
	<div class="clear"></div>
	<div class="grid_9 basics alpha omega">
		<h3><a name="visiting">Visiting this theatre</a></h3>
		<div class="noStyle"><?php echo $visiting_info;?>
		<?php echo display_ckeditor($ckeditor_visiting_info); ?>
		</div>
	</div>
	<div class="clear"></div>
	<div class="grid_9 basics alpha omega">
			<h3><a name="rel_sites">Related sites nearby</a></h3>
			<div class="noStyle"><?php echo $related_sites;?>
			<?php echo display_ckeditor($ckeditor_related_sites); ?>
			</div>
	</div>
	<div class="grid_9 basics alpha omega">
		<h3><a name="gps">GPS Coordinates</a></h3>
		<div class="noStyle">
		</div>
	</div>
	<div class="clear"></div>
	<p><input type="submit" value="Update" class="submit" /></p>                  
</div>
</form>
</div>
<!-- end featuredarea -->