<?php

	$plugin = elgg_extract("entity", $vars);
	
	$available_fields = birthdays_get_available_profile_fields();
	
	$date_array = getdate();
	$day_month = elgg_echo("date:month:" . str_pad($date_array["mon"], 2, 0, STR_PAD_LEFT), array($date_array["mday"]));
	$day_month_year = $day_month . " " . $date_array["year"];
	$old_style = elgg_view("output/date", array("value" => time()));
	
	$output_format_options = array(
		$day_month => "day_month",
		$day_month_year => "day_month_year",
		$old_style => "old_style",
	);
	
	$yesno_options = array(
			"yes" => elgg_echo("option:yes"),
			"no" => elgg_echo("option:no")
	);
	
	// field selection
	$field_selection = "<div>" . elgg_echo("birthdays:settings:field_selection:description") . "</div>";
	
	if(!empty($available_fields)){
		$options = array(
				elgg_echo("birthdays:settings:field_selection:none") => ""
		);
	
		foreach($available_fields as $metadata_name => $type){
			$label = $metadata_name;
			if(elgg_echo("profile:" . $metadata_name) != "profile:" . $metadata_name){
				$label = elgg_echo("profile:" . $metadata_name);
			}
				
			$options[$label] = $metadata_name;
		}
	
		$field_selection .= elgg_view("input/radio", array("name" => "params[birthday_field]", "options" => $options, "value" => $plugin->birthday_field));
	} else {
		$field_selection .= "<div>" . elgg_echo("notfound") . "</div>";
	}
	
	echo elgg_view_module("inline", elgg_echo("birthdays:settings:field_selection"), $field_selection);
	
	// output format
	$output_format = "<div>" . elgg_echo("birthdays:settings:output_format:description") . "</div>";
	$output_format .= elgg_view("input/radio", array("name" => "params[output_format]", "options" => $output_format_options, "value" => birthdays_get_output_date_format()));
	
	echo elgg_view_module("inline", elgg_echo("birthdays:settings:output_format"), $output_format);

	// other
	$other = "<div>";
	$other .= elgg_echo("birthdays:settings:other:limit_upcoming");
	$other .= "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[limit_upcoming]", "options_values" => $yesno_options, "value" => $plugin->limit_upcoming));
	$other .= "</div>";
	
	echo elgg_view_module("inline", elgg_echo("birthdays:settings:other"), $other);
