<?php
  $exhibit_data = array();
  $exhibit_data["items"] = array();

  /* TODO: Fill in the list of theatres into the "items" array */

  $exhibit_data["types"] = array();
  $exhibit_data["types"]["Theatre"] = array();
  $exhibit_data["types"]["Theatre"]["pluralLabel"] = "Theatres";

  $exhibit_data["types"]["City"] = array();
  $exhibit_data["types"]["City"]["pluralLabel"] = "Cities";

  $exhibit_data["properties"] = array();
  $exhibit_data["properties"]["city"] = array();
  $exhibit_data["properties"]["city"]["valueType"] = "item";
  $exhibit_data["properties"]["city"]["label"] = "city";

  $exhibit_data["properties"]["country_name"] = array();
  $exhibit_data["properties"]["country_name"]["valueType"] = "text";
  $exhibit_data["properties"]["country_name"]["label"] = "country";

  $exhibit_data["properties"]["period_rep"] = array();
  $exhibit_data["properties"]["period_rep"]["valueType"] = "text";
  $exhibit_data["properties"]["period_rep"]["label"] = "period";

  $exhibit_data["properties"]["sub_type"] = array();
  $exhibit_data["properties"]["sub_type"]["valueType"] = "text";
  $exhibit_data["properties"]["sub_type"]["label"] = "type";

  $exhibit_data["properties"]["label"] = array();
  $exhibit_data["properties"]["label"]["valueType"] = "text";
  $exhibit_data["properties"]["label"]["label"] = "name";

  foreach($theatres as $theatre) {
    
    $item = array();
    $item['label'] = $theatre['theatre_name'];
    $item['id'] = $theatre['id'];
    $item['theatre_url'] = base_url().'theatres/entry_visitor_info/'.$theatre['id'].'_top';
    $item['theatre_name'] = $theatre['theatre_name'];
    //$item['theatre_name_link'] = "<a href='" . $item['theatre_url'] . "'>" . $item['theatre_name'] . "</a>";
    $item['city'] = $theatre['city'];
    $item['thumbnail'] = base_url().$theatre['thumbnail'];
    //$item['thumbnail_link'] = "<a href='" . $item['theatre_url'] . "'><img src='" . $item['thumbnail'] . "' width='130' /></a>";
    $item['country_name'] = $theatre['country_name'];
	$item['country_digraph'] = $theatre['country_digraph'];

    if($theatre['region'] != "") {
        $item['region'] = $theatre['region'];
	}

	if($theatre['sub_type'] != '') {
    	$item['sub_type'] = $theatre['sub_type'];
	}
    $item['date_range'] = $theatre['date_range'];

	$item['period_rep'] = $theatre['period_rep'];

	$item['type'] = 'Theatre';

    if($theatre['auditorium_date'] != 0) {
		$item['auditorium_date'] = $theatre['auditorium_date'];
	}
	
	if($theatre['lat']) {
		$item['lat'] = $theatre['lat'];
	}
	
	if($theatre['lng']) {
		$item['lng'] = $theatre['lng'];
	}
	
	if(isset($username) && $username != "") {
		if(array_key_exists('Add', $theatre)) {
			$item['add'] = $theatre['Add'];
		}
		if(array_key_exists('Edit', $theatre)) {
			$item['edit'] = $theatre['Edit'];
		}	
		if(isset($access_level) && $access_level == 'administrator' && array_key_exists('Delete', $theatre)) {
			$item['delete'] = $theatre['Delete'];
		}
	}
        
    $exhibit_data["items"][] = $item;
  }

  foreach($cities as $city) {
	$item = array();
	$item['id'] = $city['id'];
	$item['label'] = $city['label'];
	$item['type'] = 'City';
	$item['aliases'] = $city['aliases'];
	$exhibit_data["items"][] = $item;
  }

  echo json_encode($exhibit_data);
?>