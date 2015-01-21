<?php
/**
 * All helper functions are bundled here
 */

/**
 * Get all the available profile fields for birthday display
 *
 * @return bool|array
 */
function birthdays_get_available_profile_fields() {
	$result = false;
	
	$profile_fields = elgg_get_config("profile_fields");
	
	if (!empty($profile_fields) && is_array($profile_fields)) {
		$found_fields = array();
		$allowed_types = array("birthday", "date");
		
		foreach ($profile_fields as $metadata_name => $type) {
			if (in_array($type, $allowed_types)) {
				$found_fields[$metadata_name] = $type;
			}
		}
		
		if (!empty($found_fields)) {
			$result = $found_fields;
		}
	}
	
	return $result;
}

/**
 * Get the profile field which is configured as birthday field
 *
 * @return bool|string
 */
function birthdays_get_configured_birthday_field() {
	static $result;
	
	if (!isset($result)) {
		$result = false;
		
		if ($setting = elgg_get_plugin_setting("birthday_field", "birthdays")) {
			$result = $setting;
		}
	}
	
	return $result;
}

/**
 * Get the output date format
 *
 * @return string
 */
function birthdays_get_output_date_format() {
	static $result;
	
	if (!isset($result)) {
		$result = "day_month";
		
		if ($setting = elgg_get_plugin_setting("output_format", "birthdays")) {
			$result = $setting;
		}
	}
	
	return $result;
}

/**
 * Get the basic options for use in ege* functions
 *
 * @param bool $register_error display an error if one occures (default: false)
 *
 * @return bool|array
 */
function birthdays_get_basic_selection_options($register_error = false) {
	$result = false;
	
	if ($field_name = birthdays_get_configured_birthday_field()) {
		$interval = 3;
		if (elgg_get_plugin_setting("limit_upcoming", "birthdays") == "no") {
			$interval = 12;
		}
		
		$result = array(
			"type" => "user",
			"metadata_name_value_pairs" => array(
				"name" => $field_name,
				"value" => "",
				"operand" => "<>"
			),
			"selects" => array(
				"DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR AS currbirthday"
			),
			"wheres" => array(
				"((((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) >= DATE(NOW()))
				AND
				(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) BETWEEN DATE(NOW()) AND DATE(NOW()) + INTERVAL $interval MONTH)
				
				OR
				
				(((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) < DATE(NOW()))
				AND
				(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 1 YEAR) BETWEEN DATE(NOW()) AND DATE(NOW()) + INTERVAL $interval MONTH)
				)"),
			"order_by" => "CASE WHEN currbirthday < DATE(NOW()) THEN currbirthday + INTERVAL 1 YEAR ELSE currbirthday END",
			"full_view" => false
		);
	} elseif ($register_error) {
		register_error(elgg_echo("birthdays:no_field_configured"));
	}
	
	return $result;
}
