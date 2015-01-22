<?php

// get basic selection options
if ($options = birthdays_get_basic_selection_options()) {

	$widget = elgg_extract("entity", $vars);
	$owner = $widget->getOwnerEntity();
	
	$who_to_show = $widget->who_to_show;
	$num_display = (int) $widget->num_display;
	if ($num_display < 1) {
		$num_display = 10;
	}
	
	$options["limit"] = $num_display;
	$options["offset"] = 0;
	$options["pagination"] = false;
	
	switch ($widget->context) {
		case "groups":
			$options["relationship"] = "member";
			$options["relationship_guid"] = $owner->getGUID();
			$options["inverse_relationship"] = true;
			break;
		case "index":
			$options["relationship"] = "member_of_site";
			$options["relationship_guid"] = $owner->getGUID();
			$options["inverse_relationship"] = true;
			break;
		default:
			if ($who_to_show == "all") {
				$options["relationship"] = "member_of_site";
				$options["relationship_guid"] = $widget->site_guid;
				$options["inverse_relationship"] = true;
			} else {
				$options["relationship"] = "friend";
				$options["relationship_guid"] = $owner->getGUID();
			}
			break;
	}
	
	// make sure we can see the birthday
	elgg_push_context("birthdays");
	
	if ($listing = elgg_list_entities_from_relationship($options)) {
		
		if (elgg_instanceof($owner, "group")) {
			$more_url = "birthdays/group/" . $owner->getGUID() . "/all";
		} else {
			$more_url = "birthdays";
		}
		
		$listing .= "<div class='elgg-widget-more'>";
		$listing .= elgg_view("output/url", array("href" => $more_url, "text" => elgg_echo("birthdays:more")));
		$listing .= "</div>";
	} else {
		$listing = elgg_echo("birthdays:none");
	}
	
	// restore context
	elgg_pop_context();
} else {
	$listing = elgg_echo("birthdays:no_field_configured");
}

echo $listing;
