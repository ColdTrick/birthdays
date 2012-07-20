<?php

	function birthdays_filter_menu_hook($hooks, $type, $return_value, $params){
		$result = $return_value;
		
		if(elgg_in_context("birthdays")){
			// remove filter items
			$remove_items = array("all", "mine");
			
			foreach ($result as $index => $menu_item){
				if(in_array($menu_item->getName(), $remove_items)){
					unset($result[$index]);
				}
			}
			
			// add new items
			$result[] = ElggMenuItem::factory(array(
				"name" => "upcoming",
				"text" => elgg_echo("birthdays:filter:upcoming"),
				"href" => "birthdays/upcoming",
				"priority" => 100
			));
		}
		
		return $result;
	}
	
	function birthdays_owner_block_menu_hook($hooks, $type, $return_value, $params){
		$result = $return_value;
		
		if(!empty($params) && is_array($params)){
			$entity = elgg_extract("entity", $params);
			
			if(elgg_instanceof($entity, "group")){
				if($entity->birthdays_enable == "yes"){
					$result[] = ElggMenuItem::factory(array(
						"name" => "birthdays",
						"text" => elgg_echo("birthdays:menu:owner_block:group:title"),
						"href" => "birthdays/group/" . $entity->getGUID() . "/all"
					));
				}
			}
		}
		
		return $result;
	}
	
	function birthdays_widget_url_hook($hooks, $type, $return_value, $params){
		$result = $return_value;
		
		if(empty($result) && !empty($params) && is_array($params)){
			$widget = elgg_extract("entity", $params);
			
			switch($widget->handler){
				case "birthdays":
					if($widget->context == "groups"){
						$result = "birthdays/group/" . $widget->getOwnerGUID() . "/all";
					} else {
						$result = "birthdays";
					}
					
					break;
			}
		}
		
		return $result;
	}