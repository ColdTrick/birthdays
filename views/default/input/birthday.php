<?php

	/**
	 * This is a wrapper view for input/date, to be sure when something changes in Elgg core you don't have to change your profile configuration
	 * 
	 * @uses $vars['value'] 	the value of the bithday field
	 * @uses $vars['name'] 		the name of the birthday field
	 * @uses $vars['class'] 	additional classes for the birthday field
	 * 
	 * Values are saved in the format yyyy-mm-dd
	 * 
	 */

	echo elgg_view("input/date", $vars);