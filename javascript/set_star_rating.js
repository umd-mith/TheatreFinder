// included in the load for the entry_visitor_info.php view
// this function:
// 1) grabs the hidden value in the entry form that stores the theatre's rating 
//		from the database,
// 2) sets the star rating to that value 
// 3) ensures that the rating for Viewing the entry (not editing) is read-only
$(function() {

	var db_rating = $('#db_star_rating').val();		
	$('.star').rating('select', db_rating);
	$('input').rating('readOnly', 'true');
});