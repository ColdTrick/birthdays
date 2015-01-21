<?php

$group = elgg_extract("entity", $vars);

if (elgg_instanceof($group, "group") && ($group->birthdays_enable == "yes")) {
	$more_link = "";
	
	// get basic options
	if ($options = birthdays_get_basic_selection_options()) {
		// more link
		$more_link = elgg_view("output/url", array(
			"text" => elgg_echo("birthdays:more"),
			"href" => "birthdays/group/" . $group->getGUID() . "/all"
		));
		
		// set options
		$options["relationship"] = "member";
		$options["relationship_guid"] = $group->getGUID();
		$options["inverse_relationship"] = true;
		$options["limit"] = 6;
		$options["offset"] = 0;
		$options["pagination"] = false;
		
		// set correct context
		elgg_push_context("birthdays");
		
		if (!($content = elgg_list_entities_from_relationship($options))) {
			$content = elgg_echo("birthdays:none");
		}
		
		// reset context
		elgg_pop_context();
	} else {
		$content = elgg_echo("birthdays:no_field_configured");
	}
	
	echo elgg_view("groups/profile/module", array(
		"title" => elgg_echo("birtdays:widget:title"),
		"content" => $content,
		"all_link" => $more_link,
	));
}
