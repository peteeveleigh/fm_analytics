<?php

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

<script type="text/javascript">
		
	//Set your ooid
	oo.setOOId("<?php echo $Settings->get('fm_analytics_ooid')->settingValue(); ?>");
	var aid 		= 	"<?php echo $Settings->get('fm_analytics_gaid')->settingValue(); ?>";

	var d = new Date();
	var df = new Date();
	var dateTo;
	var dateFrom;

	d.setDate(d.getDate());
	df.setDate(df.getDate() - 30);
	dateTo =  ('0' + (d.getMonth()+1)).slice(-2) + '/' + ('0' + d.getDate()).slice(-2) + '/' + d.getFullYear();
	dateFrom =  ('0' + (df.getMonth()+1)).slice(-2) + '/' + ('0' + df.getDate()).slice(-2) + '/' + df.getFullYear();

	/*
	var curr_day = '0' + d.getDate()).slice(-2);
	var curr_month = d.getMonth() + 1;
	var curr_year = d.getFullYear();
	*/

	document.getElementById('dateFrom').value = dateFrom;
	document.getElementById('dateTo').value = dateTo;

	showStats(dateFrom, dateTo);

	oo.load(function()
	{
		
		//Set initial date ranges
		//$('#dateFrom').val(dateFrom);
		//$('#dateTo').val(dateTo);


		$('#dateForm').on('submit',function(){
			var df = $('#dateFrom').val();
			var dt = $('#dateTo').val();
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