<?php

$base_url = elgg_extract("base_url", $vars, "birthdays/day");
$base_url = elgg_normalize_url($base_url);

$default_date = "";
$month = elgg_extract("limit_month", $vars);
$day = elgg_extract("limit_day", $vars);

if (!empty($month) && !empty($day)) {
	$date_array = getdate();
	
	$default_date = $date_array["year"] . "-" . $month . "-" . $day;
}

echo elgg_view_module("aside", elgg_echo("birthdays:sidebar:datepicker:title"), "<div id='birthdays_sidebar_datepicker'></div>");
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#birthdays_sidebar_datepicker').datepicker({
			dateFormat: 'yy-mm-dd',
			defaultDate: '<?php echo $default_date; ?>',
			onSelect: function(date, inst){
				var dateObj = new Date(date);
				
				document.location.href = "<?php echo $base_url; ?>/" + (dateObj.getMonth() + 1) + "/" + dateObj.getDate();
			}
		});

		<?php if (empty($default_date)) { ?>
		$('#birthdays_sidebar_datepicker').find('.ui-state-active').attr('class', 'ui-state-default');
		<?php } ?>
	});

</script>