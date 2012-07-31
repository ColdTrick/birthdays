<?php
	/**
	 * Elgg date input
	 * Displays a text field with a popup date picker.
	 *
	 * The elgg.ui JavaScript library initializes the jQueryUI datepicker based
	 * on the CSS class .elgg-input-birthday. It uses the ISO 8601 standard for date
	 * representation: yyyy-mm-dd.
	 *
	 * @uses $vars['value']     The current value, if any (as a unix timestamp)
	 * @uses $vars['class']     Additional CSS class
	 * 
	 */
	
	if (isset($vars['class'])) {
		$vars['class'] = "elgg-input-birthday {$vars['class']}";
	} else {
		$vars['class'] = "elgg-input-birthday";
	}
	
	$defaults = array(
		'value' => '',
		'disabled' => false,
	);
	
	$vars = array_merge($defaults, $vars);
	
	// convert timestamps to text for display
	if (is_numeric($vars['value'])) {
		$vars['value'] = gmdate('Y-m-d', $vars['value']);
	}
	
	$vars["type"] = "text";
	$vars["readonly"] = "readonly";
	
	$attributes = elgg_format_attributes($vars);
	echo "<input $attributes />";
