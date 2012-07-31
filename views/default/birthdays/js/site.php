<?php

?>
elgg.provide('elgg.birthday.init');

elgg.birthday.init = function(){
	if($('.elgg-input-birthday').length){
		$('.elgg-input-birthday').datepicker({
			dateFormat: 'yy-mm-dd',
			maxDate: new Date(),
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			hideIfNoPrevNext: true
		}).keyup(function(e) {
			if(e.keyCode == 8 || e.keyCode == 46) {
				$.datepicker._clearDate(this);
			}
		});
	}
}


elgg.register_hook_handler('init', 'system', elgg.birthday.init);