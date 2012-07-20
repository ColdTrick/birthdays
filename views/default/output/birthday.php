<?php

	/**
	 * This is a wrapper view for output/date, to be sure when something changes in Elgg core you don't have to change your profile configuration
	 * 
	 * @uses $vars['value'] 	the value of the bithday field
	 * 
	 * Supports values in the following formats:
	 * - unix timestamp
	 * - yyyy-mm-dd
	 */

	$output_format = birthdays_get_output_date_format();
	
	if($output_format == "old_style"){
		echo elgg_view("output/date", $vars);
	} else {
		$value = elgg_extract("value", $vars);
		
		if(is_numeric($value)){
			// unix timestamp
			$date_array = getdate($value);
		} else {
			// asume yyyy-mm-dd
			$date_array = getdate(strtotime($value));
		}
		
		$string = elgg_echo("date:month:" . str_pad($date_array["mon"], 2, 0, STR_PAD_LEFT), array($date_array["mday"]));
		
		if($output_format == "day_month_year"){
			$string .= " " . $date_array["year"];
		}
		
		echo $string;
	}
	