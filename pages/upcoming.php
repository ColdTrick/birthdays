<?php

	/**
	 * This page will display all the upcoming birthdays off all the users
	 * 
	 */

	// do we know which field contains birthdays
	if(!($field_name = birthdays_get_configured_birthday_field())){
		register_error(elgg_echo("birthdays:no_field_configured"));
		forward(REFERER);
	}
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"));
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:upcoming:title");
	
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
		"selects" => array(
			"DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR AS currbirthday"
		),
		"wheres" => array(
			"((((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) > NOW()) 
			AND 
			(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) BETWEEN NOW() AND NOW() + INTERVAL 3 MONTH)
			
			OR 
			
			(((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) < NOW()) 
			AND 
			(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 1 YEAR) BETWEEN NOW() AND NOW() + INTERVAL 3 MONTH)
			)"),
		"order_by" => "CASE WHEN currbirthday < NOW() THEN currbirthday + INTERVAL 1 YEAR ELSE currbirthday END",
		"full_view" => false,
	);
	
	if(!($listing = elgg_list_entities_from_relationship($options))){
		$listing = elgg_echo("birthdays:none");
	}
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $listing,
		"filter_context" => "upcoming",
		"sidebar" => elgg_view("birthdays/sidebar/datepicker")
	));
	
	// display the page
	echo elgg_view_page($title_text, $page_data);