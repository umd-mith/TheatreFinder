<div class="theatres">
	<div class="facets" id="exhibit-facets">
		<div ex:role="facet" ex:facetClass="TextSearch" ex:expression=".label, .country_name, .period_rep, .sub_type, .city" ex:facetLabel="Search"></div>
		<div ex:role="facet" ex:height="6em" ex:expression=".sub_type" ex:facetLabel="Theatre Type" ex:showMissing="false"></div>
		<div ex:role="facet" ex:height="6em" ex:expression=".city" ex:facetLabel="City" ex:showMissing="false"></div>
		<div ex:role="facet" ex:height="6em" ex:expression=".country_name" ex:facetLabel="Country" ex:showMissing="false"></div>
		<div ex:role="facet" ex:height="6em" ex:expression=".region" ex:facetLabel="Region" ex:showMissing="false"></div>
		<div ex:role="facet" ex:height="6em" ex:expression=".period_rep" ex:facetLabel="Period" ex:showMissing="false"></div>
	</div>
	<div class="results">
		<div ex:role="viewPanel">
			<div ex:role="lens" class="theatre" style="display: none;">
				<div class="title"><a ex:href-content=".theatre_url" target="_blank"><span ex:content=".label"></span></a></div>
			    <div class="titling">
			    	<div class="thumbnail">
				    	<a ex:href-content=".theatre_url" target="_blank"><img width="130" ex:src-content=".thumbnail" /></a>
					</div>
				</div>
				<div class="location">
					Location: <span ex:content=".city"></span>,
					          <span ex:content=".country_name"></span>/<span ex:content=".country_digraph"></span>
							  <span ex:if-exists=".region">(<span ex:content=".region"></span>)</span>
				</div>
				<div class="timing">
					<div class="date">Period: <span ex:content=".period_rep"></span> (<span ex:content=".date_range"></span>)</div>
				</div>
				<div class="classification">
					<div class="type">Type: <span ex:content=".sub_type"></span> </div>
				</div>
				<div class="operations">
					<a ex:if-exists=".edit" class="op" ex:href-content=".edit" target="_blank">Edit</a>
					<a ex:if-exists=".delete" class="op" ex:href-subcontent="javascript:confirmDeleteTheatre('{{.delete}}')">Delete</a>
					<a ex:if-exists=".add" class="op" ex:href-content=".add" target="_blank">Add New</a>
				</div>
				<div class="clear-both"></div>
			</div>
			<!-- div ex:role="view"
				ex:viewClass="Tabular"
				ex:columns=".theatre_name_link, .thumbnail_link, .sub_type, .country_name, .period_rep"
				ex:columnFormats="list, list, list, list, list"
				ex:columnLabels="name, photo, type, country, period"
				ex:sortColumn="0"
				ex:grouped="false"
				ex:sortAscending="true">
			</div>
			<div ex:role="view"
				ex:viewClass="Timeline"
				ex:start=".date_range"
				ex:topBandUnit="decade"
				ex:bottomBandUnit="century"
				></div -->
			<div ex:role="view"
			ex:viewClass="Thumbnail"
			    ex:orders=".country_name, .city"
			    ex:possibleOrders=".label, .sub_type, .city, .country_name, .period_rep, .region"
				ex:grouped="true"></div>
		</div>		
	</div>
	<div class="clear-both"></div>
</div>