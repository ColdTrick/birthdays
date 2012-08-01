<?php

	$page_owner = elgg_get_page_owner_entity();
	if(empty($page_owner) || !elgg_instanceof($page_owner, "group")){
		register_error(elgg_echo("pageownerunavailable", array(elgg_get_page_owner_guid())));
		forward("birthdays");
	}
	
	// make sure no unauthorized persons can view this
	group_gatekeeper();
	
	// does the group have birthdays enabled
	if($page_owner->birthdays_enable != "yes"){
		register_error(elgg_echo("birthdays:groups:not_enabled"));
		forward($page_owner->getURL());
	}
	
	// do we know which field contains birthdays
	if(!($field_name = birthdays_get_configured_birthday_field())){
		register_error(elgg_echo("birthdays:no_field_configured"));
		forward(REFERER);
	}
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"), "birthdays");
	elgg_push_breadcrumb($page_owner->name);
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:group:title", array($page_owner->name));
	
	$options = array(
		"type" => "user",
		"relationship" => "member",
		"relationship_guid" => $page_owner->getGUID(),
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
		"pagination" => false
	);
	
	if(!($listing = elgg_list_entities_from_relationship($options))){
		$listing = elgg_echo("birthdays:none");
	}
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $listing,
		"filter" => ""
	));
	
	// display the page
	echo elgg_view_page($title_text, $page_data);