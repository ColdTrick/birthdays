<?php

require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");
require_once(dirname(__FILE__) . "/lib/page_handlers.php");

// register default Elgg events
elgg_register_event_handler("init", "system", "birthdays_init");
elgg_register_event_handler("pagesetup", "system", "birthdays_pagesetup");

/**
 * Called during system init
 *
 * @return void
 */
function birthdays_init() {
	
	if (elgg_is_active_plugin("profile_manager")) {
		// add custom profile field type
		// only works with Profile Manager active
		$profile_options = array(
			"show_on_register" => true,
			"mandatory" => true,
			"user_editable" => true,
			"output_as_tags" => false,
			"admin_only" => true,
			"count_for_completeness" => true
		);
		
		profile_manager_add_custom_field_type("custom_profile_field_types", "birthday", elgg_echo("brithdays:profile_field:type"), $profile_options);
	}
	
	// register page handler for nice URL's
	elgg_register_page_handler("birthdays", "birthdays_page_handler");
	
	// extend views
	elgg_extend_view("user/status", "birthdays/user_status", 600);
	elgg_extend_view("js/elgg", "birthdays/js/site");
	
	// add widget
	elgg_register_widget_type("birthdays", elgg_echo("birthdays:widget:title"), elgg_echo("birthdays:widget:description"), array("profile", "dashboard", "groups", "index"));
	
	// add group option to show birthdays
	add_group_tool_option("birthdays", elgg_echo("birthdays:groups:options"), false);
	elgg_extend_view("groups/tool_latest", "birthdays/group_module");
	
	// register plugin hooks
	elgg_register_plugin_hook_handler("register", "menu:filter", "birthdays_filter_menu_hook");
	elgg_register_plugin_hook_handler("register", "menu:owner_block", "birthdays_owner_block_menu_hook");
	elgg_register_plugin_hook_handler("widget_url", "widget_manager", "birthdays_widget_url_hook");
}

/**
 * called during page setup
 *
 * @return void
 */
function birthdays_pagesetup() {
	
	// add top menu item
	elgg_register_menu_item("site", array(
		"name" => "birthdays",
		"text" => elgg_echo("birthdays:menu:site:title"),
		"href" => "birthdays"
	));
	
	if ($page_owner = elgg_get_page_owner_entity()) {
		// do we have a group
		if (elgg_instanceof($page_owner, "group")) {
			// check if the group wishes to show birthdays
			if ($page_owner->birthdays_enable != "yes") {
				// no, so unregister stuff
				elgg_unregister_widget_type("birthdays");
			}
		}
	}
}
