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