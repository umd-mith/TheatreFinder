<table width="100%">
	<tr valign="top">
		<td ex:role="viewPanel">
			<div ex:role="lens" class="theatre">
			  <table>
				<tr>
					<td colspan="2" align="center"><img width="130" ex:src-content=".thumbnail" /></td>
				</tr>
				<tr>
				    <td colspan="2" align="center"><span ex:content=".label" class="name"></span></td>
				</tr><tr>
					<td align="right" width="60">Country:</td>
					<td><span ex:content=".country_name" class="country"></span></td>
				</tr>
			  </table>
			</div>
			<div ex:role="view"
				ex:viewClass="Tabular"
				ex:columns=".label, .thumbnail, .sub_type, .period"
				ex:columnFormats="list, image, list, list"
				ex:columnLabels="name, photo, type, period"
				ex:sortColumn="1"
				ex:grouped="false"
				ex:sortAscending="true">
			</div>
			<div ex:role="view"
			ex:viewClass="Thumbnail"
			    ex:orders=".label"
			    ex:possibleOrders=".label, .sub_type, .period, .region"
				ex:grouped="false"></div>
		</td>
		<td width="25%">
			<div ex:role="facet" ex:facetClass="TextSearch" ex:expression=".label" ex:facetLabel="Search"></div>
			<div ex:role="facet" ex:facetClass="NumericRange" ex:expression=".date" ex:interval="10" ex:facetLabel="Year"></div>
			<div ex:role="facet" ex:expression=".sub_type" ex:facetLabel="Theatre Type"></div>
			<div ex:role="facet" ex:expression=".country_name" ex:facetLabel="Country"></div>
			<div ex:role="facet" ex:expression=".period_rep" ex:facetLabel="Period"></div>
			
		</td>
	</tr>
</table>