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
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="pageviews"></span> Page Views</li>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="visitors"></span> Visitors</li>
			<li class="metric"><span style="font-size:1.2em;font-weight:bold;" id="bounces"></span> Bounces</li>
		</ul>	
	</div>	
</div>







<script src="/controlpanel/addons/apps/fm_analytics/js/oocharts.js" type="text/javascript"></script>

<script type="text/javascript">
		
	//Set your ooid
	oo.setOOId("<?php echo $Settings->get('fm_analytics_ooid')->settingValue(); ?>");
	var aid 		= 	"<?php echo $Settings->get('fm_analytics_gaid')->settingValue(); ?>";

	var dateFrom 	= 	new Date();
	var dateTo 		= 	new Date();
	

	oo.load(function()
	{
		var dateFrom 	= 	new Date();
		var dateTo 		= 	new Date();
		showStats(dateFrom, dateTo);

	
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

		var bounces = new oo.Metric(aid, dateFrom, dateTo);
		bounces.setMetric('ga:visitors');
		bounces.draw('bounces');

	}

	
</script>