<div class="grid_12 breadcrumbs">
	<p>TheatreFinder > List of theatres (<em>Current Total: <?php echo $numTheatres?></em>)</p>
</div>
    
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<h1>List of Theatres</h1>
		   
     		<div class="theatreList">
				<?php foreach($theatres as $theatre):?>	
				<div class="headerRow grid_12 alpha omega">
				<!--	<font color="#2c6871"> -->
					<div class="grid_2 alpha row1"><h4>Theatre</h4></div>
					<div class="grid_2"><h4>Country</h4></div>
					<div class="grid_2"><h4>City</h4></div>
					<div class="grid_2"><h4>Region</h4></div>
					<div class="grid_2"><h4>Date</h4></div>
					<div class="grid_2 omega"><h4>Type</h4></div>
					<!--</font>-->
				</div>						
				<div class="theatreEntries grid_12 alpha omega">
					<div class="grid_2 alpha row1">
						<h5><?php echo $theatre['theatre_name'];?></h5>
						<a name="<?php echo $theatre['id'];?>"><img src="<?php echo base_url();?><?php echo $theatre['thumbnail'];?>" alt="thumb" /></a>
					</div>
					<div class="grid_2"><a name="<?php echo $theatre['prev'];?>"><?php echo $theatre['country_name']." (".$theatre['country_digraph'].")";?></a></div>
					<div class="grid_2"><?php echo $theatre['city'];?></div>
					<div class="grid_2"><?php echo $theatre['region'];?></div>
					<div class="grid_2"><?php echo $theatre['date_range'].'<br>'.$theatre['period_rep'];?></div>
					<div class="grid_2 omega"><?php echo $theatre['sub_type'];?></div>
				</div>
				<div class="editRow grid_12 alpha omega">
					<p><img src="<?php echo  base_url();?>images/icon_viewDetails.png" class="icon" alt="View Details" /><?php echo $theatre['Details'];?> 
					<span class="right_link"><a href="#top_of_navbar">Back to Top</a></span></p>
				</div>
				<?php endforeach;?>
     	
      		<!--<div class="grid_2 prefix_10"><h3><?php echo anchor('theatreCtrl/addTheatreForm/', '+ Add a new entry');?></h3></div>-->
       
       </div>