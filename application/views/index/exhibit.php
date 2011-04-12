<table width="100%">
	<tr valign="top">
		<td ex:role="viewPanel">
			<table ex:role="lens" class="theatre">
				<tr>
					<td><img ex:src-content=".theatreImageURL" /></td>
					<td>
						<div ex:content=".label" class="name"></div>
					</td>
				</tr>
			</table>
			<div ex:role="view"
				ex:viewClass="Exhibit.TabularView"
				ex:columns=".label, .imageURL, .type"
				ex:columnFormats="list, image, list"
				ex:columnLabels="name, photo, type"
				ex:sortColumn="1"
				ex:sortAscending="true">
			</div>
			<div ex:role="view"
			    ex:orders=".label"
			    ex:possibleOrders=".label"
				ex:grouped="true"></div>
		</td>
		<td width="25%">
			<div ex:role="facet" ex:facetClass="TextSearch" ex:expression=".label" ex:facetLabel="Search"></div>
			<div ex:role="facet" ex:facetClass="NumericRange" ex:expression=".date" ex:interval="10" ex:facetLabel="Year"></div>
			<div ex:role="facet" ex:expression=".type" ex:facetLabel="Theatre Type"></div>
		</td>
	</tr>
</table>