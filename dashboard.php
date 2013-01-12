<?php
/*

fm_analytics - https://github.com/fantasticmachine/fm_analytics
------------
Author: Pete Eveleigh / Fantastic Machine
Twitter: @foamcow / @fmachine
Copyright © 2013 Pete Eveleigh | BSD & MIT license


Pikaday - https://github.com/dbushell/Pikaday
------------
Pikaday date picker provided by David Bushell
http://dbushell.com](http://dbushell.com/
Copyright © 2012 David Bushell | BSD & MIT license
*/




$Lang  = $API->get('Lang');

$Settings = $API->get('Settings');
?>


<div class="widget">
  <h2><?php echo $Lang->get('Site Visitor Stats'); ?></h2>
 	<form id="dateForm" method="post" action="#">
  		<fieldset id="daterange">
  			<label for="dateFrom">From <input type="text" id="dateFrom" placeholder="mm/dd/yyyy"></label>
  			<label for="dateTo">To <input type="text" id="dateTo" placeholder="mm/dd/yyyy"></label>
  			<input class="button" type="submit" value="Refresh">
  		</fieldset>
	</form>
	<div class="bd">
		<ul>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="visitors"></span> Visitors</li>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="newvisitors"></span> New Visitors</li>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="pageviews"></span> Page Views</li>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="bounces"></span> Bounces</li>
		</ul>	
	</div>	
</div>




<script src="<?php echo PERCH_LOGINPATH.'/addons/apps/fm_analytics/js/oocharts.js';?>" type="text/javascript"></script>

<script src="<?php echo PERCH_LOGINPATH.'/addons/apps/fm_analytics/js/pikaday.min.js';?>" type="text/javascript"></script>

<script type="text/javascript">
		
	//Set your ooid
	oo.setOOId("<?php echo $Settings->get('fm_analytics_ooid')->settingValue(); ?>");
	var aid = "<?php echo $Settings->get('fm_analytics_gaid')->settingValue(); ?>";

	var d = new Date();
	var df = new Date();
	var dateTo;
	var dateFrom;

	d.setDate(d.getDate());
	df.setDate(df.getDate() - 30); // set the dateFrom to be 30 days before the current date

	// do some string manipulation to zero pad the day and  month portions of the date
	dateTo =  ('0' + (d.getMonth()+1)).slice(-2) + '/' + ('0' + d.getDate()).slice(-2) + '/' + d.getFullYear();
	dateFrom =  ('0' + (df.getMonth()+1)).slice(-2) + '/' + ('0' + df.getDate()).slice(-2) + '/' + df.getFullYear();



	document.getElementById('dateFrom').value = dateFrom;
	document.getElementById('dateTo').value = dateTo;

	// call the stats display function immediately
	showStats(dateFrom, dateTo);

	// bind the date picker widget to the form fields
	var pickerFrom = new Pikaday({ field: document.getElementById('dateFrom'), format: 'mm/dd/yyyy' });
	var pickerTo = new Pikaday({ field: document.getElementById('dateTo'), format: 'mm/dd/yyyy' });


	oo.load(function()
	{
		$('#dateForm').on('submit',function(){
			var df = document.getElementById('dateFrom').value;
			var dt = document.getElementById('dateTo').value;
			showStats(df,dt);
			return false;
		})
	});

	function showStats(df, dt){
		var dateFrom 	= 	new Date(df);
		var dateTo 		= 	new Date(dt);

		var pageviews = new oo.Metric(aid, dateFrom, dateTo);
		pageviews.setMetric('ga:pageviews');
		pageviews.draw('pageviews');


		var visitors = new oo.Metric(aid, dateFrom, dateTo);
		visitors.setMetric('ga:visitors');
		visitors.draw('visitors');

		var newvisitors = new oo.Metric(aid, dateFrom, dateTo);
		newvisitors.setMetric('ga:newVisits');
		newvisitors.draw('newvisitors');

		var bounces = new oo.Metric(aid, dateFrom, dateTo);
		bounces.setMetric('ga:bounces');
		bounces.draw('bounces');

	}

	
</script>


<link rel="stylesheet" type="text/css" href="<?php echo PERCH_LOGINPATH.'/addons/apps/fm_analytics/css/pikaday.css';?>">