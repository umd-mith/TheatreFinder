<table width="98%">
	<tr valign="top">
		<td ex:role="viewPanel">
			<div ex:role="lens" class="theatre">
			  <table>
				<tr>
					<td colspan="2" align="center"><a ex:href-content=".theatre_url"><img width="130" ex:src-content=".thumbnail" /></a></td>
				</tr>
				<tr>
				    <td colspan="2" align="center"><a ex:href-content=".theatre_url"><span ex:content=".label"></span></a></td>
				</tr><tr>
					<td align="right" width="60">Country:</td>
					<td><span ex:content=".country_name" class="country"></span></td>
				</tr>
			  </table>
			</div>
			<div ex:role="view"
				ex:viewClass="Tabular"
				ex:columns=".theatre_name_link, .thumbnail_link, .sub_type, .period_rep"
				ex:columnFormats="list, list, list, list"
				ex:columnLabels="name, photo, type, period"
				ex:sortColumn="0"
				ex:grouped="false"
				ex:sortAscending="true">
			</div>
			<div ex:role="view"
			ex:viewClass="Thumbnail"
			    ex:orders=".label"
			    ex:possibleOrders=".label, .sub_type, .period_rep, .region"
				ex:grouped="false"></div>
		</td>
		<td width="25%">
			<div ex:role="facet" ex:facetClass="TextSearch" ex:expression=".label" ex:facetLabel="Search"></div>
			<div ex:role="facet" ex:expression=".sub_type" ex:facetLabel="Theatre Type" ex:showMissing="false"></div>
			<div ex:role="facet" ex:expression=".country_name" ex:facetLabel="Country" ex:showMissing="false"></div>
			<div ex:role="facet" ex:expression=".period_rep" ex:facetLabel="Period" ex:showMissing="false"></div>
			
		</td>
	</tr>
</table>