<?php
  function x($s) {
    //return iconv("UTF-8", "UTF-8//IGNORE", $s);	
    $encoding = @mb_detect_encoding($s, 'UTF-8, ISO-8859-1, ASCII', true);
	if(!$encoding) $encoding = 'UTF-8';
	return @iconv($encoding, "UTF-8//IGNORE", $s);
  }

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

  $exhibit_data["properties"]["normalized_country_name"] = array();
  $exhibit_data["properties"]["normalized_country_name"]["valueType"] = "text";
  $exhibit_data["properties"]["normalized_country_name"]["label"] = "country";

  $exhibit_data["properties"]["period_rep"] = array();
  $exhibit_data["properties"]["period_rep"]["valueType"] = "text";
  $exhibit_data["properties"]["period_rep"]["label"] = "period";

  $exhibit_data["properties"]["sub_type"] = array();
  $exhibit_data["properties"]["sub_type"]["valueType"] = "text";
  $exhibit_data["properties"]["sub_type"]["label"] = "type";

  $exhibit_data["properties"]["label"] = array();
  $exhibit_data["properties"]["label"]["valueType"] = "text";
  $exhibit_data["properties"]["label"]["label"] = "name";

  $exhibit_data["properties"]["normalized_label"] = array();
  $exhibit_data["properties"]["normalized_label"]["valueType"] = "text";
  $exhibit_data["properties"]["normalized_label"]["label"] = "name";

  foreach($theatres as $theatre) {
    
    $item = array();
    $item['label'] = x($theatre['theatre_name']);
    $item['id'] = $theatre['id'];
    $item['theatre_url'] = base_url().'theatres/entry_visitor_info/'.$theatre['id'].'_top';
    $item['theatre_name'] = x($theatre['theatre_name']);
    $item['normalized_label'] = strtolower($item['theatre_name']);
    //$item['theatre_name_link'] = "<a href='" . $item['theatre_url'] . "'>" . $item['theatre_name'] . "</a>";
    $item['city'] = x($theatre['city']);
    $item['thumbnail'] = base_url().$theatre['thumbnail'];
    //$item['thumbnail_link'] = "<a href='" . $item['theatre_url'] . "'><img src='" . $item['thumbnail'] . "' width='130' /></a>";
    $item['country_name'] = x($theatre['country_name']);
    $item['normalized_country_name'] = strtolower($item['country_name']);
	$item['country_digraph'] = x($theatre['country_digraph']);

    if($theatre['region'] != "") {
        $item['region'] = x($theatre['region']);
	}

	if($theatre['sub_type'] != '') {
    	$item['sub_type'] = x($theatre['sub_type']);
	}
    $item['date_range'] = x($theatre['date_range']);

	$item['period_rep'] = x($theatre['period_rep']);

	$item['type'] = 'Theatre';

    if($theatre['auditorium_date'] != 0) {
		$item['auditorium_date'] = x($theatre['auditorium_date']);
	}
	
	if($theatre['lat']) {
		$item['lat'] = x($theatre['lat']);
	}
	
	if($theatre['lng']) {
		$item['lng'] = x($theatre['lng']);
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
	$item['id'] = x($city['id']);
	$item['label'] = x($city['label']);
	$item['type'] = 'City';
	$item['aliases'] = array();
	foreach($city['aliases'] as $a) {
		$item['aliases'][] = x($a);
	}
	$exhibit_data["items"][] = $item;
  }

  echo @json_encode($exhibit_data);
?>