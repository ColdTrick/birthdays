<?php
/**
 * All page handlers are bunded here
 */

/**
 * The birthday page handler
 *
 * @param array $page the url segments
 *
 * @return bool
 */
function birthdays_page_handler($page) {
	
	switch ($page[0]) {
		case "upcoming":
			include(dirname(dirname(__FILE__)) . "/pages/upcoming.php");
			break;
		case "day":
			if (isset($page[1])) {
				set_input("limit_month", $page[1]);
			}
			
			if (isset($page[2])) {
				set_input("limit_day", $page[2]);
			}
			
			include(dirname(dirname(__FILE__)) . "/pages/day.php");
			break;
		case "friends":
			include(dirname(dirname(__FILE__)) . "/pages/friends.php");
			break;
		case "group":
			include(dirname(dirname(__FILE__)) . "/pages/group.php");
			break;
		default:
			forward("birthdays/upcoming");
			break;
	}
	
	return true;
}