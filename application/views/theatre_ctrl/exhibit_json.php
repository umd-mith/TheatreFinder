<?php
  $exhibit_data = array();
  $exhibit_data["items"] = array();

  /* TODO: Fill in the list of theatres into the "items" array */

  $exhibit_data["types"] = array();
  $exhibit_data["types"]["Theatre"] = array();
  $exhibit_data["types"]["Theatre"]["pluralLabel"] = "Theatres";

  $exhibit_data["properties"] = array();

  echo json_encode($exhibit_data);
?>