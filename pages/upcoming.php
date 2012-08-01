<?php

	/**
	 * This page will display all the upcoming birthdays off all the users
	 * 
	 */

	// can we get the basic selection options
	if(!($options = birthdays_get_basic_selection_options(true))){
		forward(REFERER);
	}
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"));
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:upcoming:title");
	
	// make sure we have the correct relationship
	$options["relationship"] = "member_of_site";
	$options["relationship_guid"] = elgg_get_site_entity()->getGUID();
	$options["inverse_relationship"] = true;
	
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