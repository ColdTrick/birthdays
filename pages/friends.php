<?php

	$page_owner = elgg_get_page_owner_entity();
	// no page owner set, may the current users
	if(empty($page_owner) && elgg_is_logged_in()){
		$page_owner = elgg_get_logged_in_user_entity();
		
		// set page owner
		elgg_set_page_owner_guid($page_owner->getGUID());
	}
	
	if(empty($page_owner)){
		register_error(elgg_echo("pageownerunavailable", array(elgg_get_page_owner_guid())));
		forward("birthdays");
	}
	
	// can we get the basic selection options
	if(!($options = birthdays_get_basic_selection_options(true))){
		forward(REFERER);
	}
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:friends:title", array($page_owner->name));
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"), "birthdays");
	elgg_push_breadcrumb($title_text);
	
	// make sure we have the correct relationship
	$options["relationship"] = "friend";
	$options["relationship_guid"] = $page_owner->getGUID();
	// make sure we can see all our friends
	unset($options["wheres"]);
	
	if(!($listing = elgg_list_entities_from_relationship($options))){
		$listing = elgg_echo("birthdays:none");
	}
	
	// build page
	$page_data = elgg_view_layout("content", array(
		"title" => $title_text,
		"content" => $listing,
		"filter_context" => "friends",
		"sidebar" => elgg_view("birthdays/sidebar/datepicker")
	));
	
	// display the page
	echo elgg_view_page($title_text, $page_data);