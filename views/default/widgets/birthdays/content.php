<?php

	if($field_name = birthdays_get_configured_birthday_field()){
	
		$widget = elgg_extract("entity", $vars);
		$owner = $widget->getOwnerEntity();
		
		$who_to_show = $widget->who_to_show;
		$num_display = (int) $widget->num_display;
		if($num_display < 1){
			$num_display = 10;
		}
		
		$options = array(
			"type" => "user",
			"limit" => $num_display,
			"offset" => 0,
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
		
		switch($widget->context){
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
				if($who_to_show == "all"){
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
		
		if($listing = elgg_list_entities_from_relationship($options)){
			
			if(elgg_instanceof($owner, "group")){
				$more_url = $vars["url"] . "birthdays/group/" . $owner->getGUID() . "/all";
			} else {
				$more_url = $vars["url"] . "birthdays";
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