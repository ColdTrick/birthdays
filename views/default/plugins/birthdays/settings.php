<?php

	$plugin = elgg_extract("entity", $vars);
	
	$available_fields = birthdays_get_available_profile_fields();
	$output_format = birthdays_get_output_date_format();
	
	$date_array = getdate();
	$day_month = elgg_echo("date:month:" . str_pad($date_array["mon"], 2, 0, STR_PAD_LEFT), array($date_array["mday"]));
	$day_month_year = $day_month . " " . $date_array["year"];
	$old_style = elgg_view("output/date", array("value" => time()));
	
	$output_format_options = array(
		$day_month => "day_month",
		$day_month_year => "day_month_year",
		$old_style => "old_style",
	);
	
	// field selection
	echo "<div>" . elgg_echo("birthdays:settings:field_selection:description") . "</div>";
	
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
		
		echo elgg_view("input/radio", array("name" => "params[birthday_field]", "options" => $options, "value" => $plugin->birthday_field, "class" => "mbm"));
	} else {
		echo "<div>" . elgg_echo("notfound") . "</div>";
	}
	
	// output format
	echo "<div>" . elgg_echo("birthdays:settings:output_format:description") . "</div>";
	echo elgg_view("input/radio", array("name" => "params[output_format]", "options" => $output_format_options, "value" => $output_format, "class" => "mbm"));