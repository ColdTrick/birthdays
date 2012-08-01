<?php

	function birthdays_get_available_profile_fields(){
		$result = false;
		
		$profile_fields = elgg_get_config("profile_fields");
		
		if(!empty($profile_fields) && is_array($profile_fields)){
			$found_fields = array();
			$allowed_types = array("birthday", "date");
			
			foreach($profile_fields as $metadata_name => $type){
				if(in_array($type, $allowed_types)){
					$found_fields[$metadata_name] = $type;
				}
			}
			
			if(!empty($found_fields)){
				$result = $found_fields;
			}
		}
		
		return $result;
	}
	
	function birthdays_get_configured_birthday_field(){
		static $result;
		
		if(!isset($result)){
			$result = false;
			
			if($setting = elgg_get_plugin_setting("birthday_field", "birthdays")){
				$result = $setting;
			}
		}
		
		return $result;
	}
	
	function birthdays_get_output_date_format(){
		static $result;
		
		if(!isset($result)){
			$result = "day_month";
			
			if($setting = elgg_get_plugin_setting("output_format", "birthdays")){
				$result = $setting;
			}
		}
		
		return $result;
	}
	
	function birthdays_get_basic_selection_options($register_error = false){
		$result = false;
		
		if($field_name = birthdays_get_configured_birthday_field()){
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
					"((((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) > NOW()) 
					AND 
					(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) BETWEEN NOW() AND NOW() + INTERVAL 3 MONTH)
					
					OR 
					
					(((DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 0 YEAR) < NOW()) 
					AND 
					(DATE(msv1.string) + INTERVAL(YEAR(NOW()) - YEAR(DATE(msv1.string))) + 1 YEAR) BETWEEN NOW() AND NOW() + INTERVAL 3 MONTH)
					)"),
				"order_by" => "CASE WHEN currbirthday < NOW() THEN currbirthday + INTERVAL 1 YEAR ELSE currbirthday END",
				"full_view" => false
			);
		} elseif($register_error){
			register_error(elgg_echo("birthdays:no_field_configured"));
		}
		
		return $result;
	}