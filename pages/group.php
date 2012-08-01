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
	
	// can we get the basic selection options
	if(!($options = birthdays_get_basic_selection_options(true))){
		forward(REFERER);
	}
	
	// breadcrumb
	elgg_push_breadcrumb(elgg_echo("birthdays:breadcrumb:all"), "birthdays");
	elgg_push_breadcrumb($page_owner->name);
	
	// build page elements
	$title_text = elgg_echo("birthdays:page:group:title", array($page_owner->name));
	
	// make sure we have the correct relationship
	$options["relationship"] = "member";
	$options["relationship_guid"] = $page_owner->getGUID();
	$options["inverse_relationship"] = true;
	// make sure we can see all group members
	unset($options["wheres"]);
	
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