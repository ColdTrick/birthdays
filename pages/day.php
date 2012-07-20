<?php

	/**
	 * This page will display all the birthdays off the given day
	 * 
	 */

	// do we know which field contains birthdays
	if(!($field_name = birthdays_get_configured_birthday_field())){
		register_error(elgg_echo("birthdays:no_field_configured"));
		forward(REFERER);
	}
	
	// get inputs
	$limit_month = (int) get_input("limit_month");
	$limit_day = (int) get_input("limit_day");
	
	if($limit_month < 1 || $limit_month > 12){
		unset($limit_month);
	}
	
	if($limit_day < 1 || $limit_day > 31){
		unset($limit_day);
	}
	
	// we need at least a month
	if(empty($limit_month)){
		forward("birthdays");
	}
	
	// make a readable date string
	$date_string = trim(elgg_echo("date:month:" . str_pad($limit_month, 2, 0, STR_PAD_LEFT), array($limit_day)));
	
	// add filter tab
	elgg_register_menu_item("filter", array(
		"name" => "day",
		"text" => $date_string,
		"href" => current_page_url(),
		"priority" => 150
	));
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:day:title", array($date_string));
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"), "birthdays");
	elgg_push_breadcrumb($title_text);
	
	$options = array(
		"type" => "user",
		"relationship" => "member_of_site",
		"relationship_guid" => elgg_get_site_entity()->getGUID(),
		"inverse_relationship" => true,
		"metadata_name_value_pairs" => array(
			"name" => $field_name,
			"value" => "",
			"operand" => "<>"
		),
		"wheres" => array(
			"MONTH(DATE(msv1.string)) = " . $limit_month
		)
	);
	
	if(!empty($limit_day)){
		$options["wheres"][] = "DAY(DATE(msv1.string)) = " . $limit_day;
	}
	
	if(!($listing = elgg_list_entities_from_relationship($options))){
		$listing = elgg_echo("birthdays:none");
	}
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $listing,
		"filter_context" => "day",
		"sidebar" => elgg_view("birthdays/sidebar/datepicker", array("limit_month" => $limit_month, "limit_day" => $limit_day))
	));
	
	// display the page
	echo elgg_view_page($title_text, $page_data);