<?php

	$group = elgg_extract("entity", $vars);
	
	if(elgg_instanceof($group, "group") && ($group->birthdays_enable == "yes")){
		$more_link = "";
		
		if($field_name = birthdays_get_configured_birthday_field()){
			// more link
			$more_link = elgg_view("output/url", array(
				"text" => elgg_echo("birthdays:more"),
				"href" => "birthdays/group/" . $group->getGUID() . "/all"
			));
			
			// set options
			$options = array(
				"type" => "user",
				"relationship" => "member",
				"relationship_guid" => $group->getGUID(),
				"inverse_relationship" => true,
				"limit" => 6,
				"offset" => 0,
				"metadata_name_value_pairs" => array(
					"name" => $field_name,
					"value" => "",
					"operand" => "<>"
				),
				"selects" => array(
					"DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR AS currbirthday"
				),
				"order_by" => "CASE WHEN currbirthday < NOW() THEN currbirthday + INTERVAL 1 YEAR ELSE currbirthday END",
				"full_view" => false,
				"pagination" => false
			);
			
			// set correct context
			elgg_push_context("birthdays");
			
			if(!($content = elgg_list_entities_from_relationship($options))){
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