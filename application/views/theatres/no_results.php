<div class="grid_12 breadcrumbs">
	<p> TheatreFinder > Search results for <?php echo $search_phrase?> (<em>Total: <?php echo $numTheatres?></em>)</p>
</div>
    
<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<h1>No Results</h1>
		<div class="theatreList">
		</div>
		<div class="prefix_2 grid_8 suffix_2">
		<h3>No results for the search terms: <?php echo $search_phrase?></h3>
		<p>To help decide how to search for the theatres in which you are interested,
		here are some details about the basic search:
		</p>
		<p>If you select 'all' with no text in the search box, the search will return all the theatres
		in our database.  If you select 'all' with text, all of the following categories will be searched:
		<ul>
			<li>Country [Example: Greece]</li>
			<li>City (to include any alternate names we have for a city, for example: Wein for Vienna,
			Athénes or Atene for Athens) [Example: Beijing]</li>
			<li>Period (or sub-type) [Example: Baroque]</li>
			<li>Dates (House dates and general dates divided as BCE or CE) [Example: 1610-1740)</li>
			<li>Theatre names (to include any alternate names we have for theatres) [Example: Schloss]</li>
		</ul>
		</p>
		<p>If you select Country, the search will look for countries that match the
		search criteria exactly.  For example, the search: city => "czech" for theatres in the "Czech Republic"
		will not return any theatres, because the search engine is looking for the complete country name ("Czech Republic").
		</p>
		<p>If you search for City, Period or Theatre name, the search results should return matches on partial or alternate
		names, such as 'Vien' short for 'Vienna' or 'Renais' short for 'Renaissance.'
		</p>		
		<p> Dates can be searched in valid ranges (e.g., 1600-1800, 200-100 BCE) or for exact dates (e.g., 1664).
		</p>
		</div>
</div>