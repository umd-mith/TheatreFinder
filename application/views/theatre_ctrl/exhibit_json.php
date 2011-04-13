<?php
  $exhibit_data = array();
  $exhibit_data["items"] = array();

  /* TODO: Fill in the list of theatres into the "items" array */

  $exhibit_data["types"] = array();
  $exhibit_data["types"]["Theatre"] = array();
  $exhibit_data["types"]["Theatre"]["pluralLabel"] = "Theatres";

  $exhibit_data["properties"] = array();

  foreach($theatres as $theatre) {
    
    $item = array();
    $item['label'] = $theatre['theatre_name'];
    $item['id'] = $theatre['id'];
    $item['theatre_url'] = base_url().'theatre_view/entry_visitor_info/'.$theatre['id'].'_top';
    $item['theatre_name'] = $theatre['theatre_name'];
    $item['city'] = $theatre['city'];
    $item['thumbnail'] = base_url().$theatre['thumbnail'];
    $item['country_name'] = $theatre['country_name'];
    $item['region'] = $theatre['region'];
    $item['sub_type'] = $theatre['sub_type'];
    $item['date_range'] = $theatre['date_range'];
    $item['period_rep'] = $theatre['period_rep'];
        
    $exhibit_data["items"][] = $item;
  }

  echo json_encode($exhibit_data);
?>