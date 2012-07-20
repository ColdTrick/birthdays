<?php

	/** 
	 * this view extends user/status to show birthday
	 */ 

	if(elgg_in_context("birthdays")){
		$entity = elgg_extract("entity", $vars);
		
		if($field_name = birthdays_get_configured_birthday_field()){
			$birthday = $entity->$field_name;
			
			if(!empty($birthday)){
				$string = $field_name;
				if(elgg_echo("profile:" . $field_name) != "profile:" . $field_name){
					$string = elgg_echo("profile:" . $field_name);
				}
				echo "<div>" . $string . ": " . elgg_view("output/birthday", array("value" => $birthday)) . "</div>";
			}
		}
	}