<?php

$Lang  = $API->get('Lang');

$Settings = $API->get('Settings');
?>




<script src="/controlpanel/addons/apps/fm_analytics/js/oocharts.js" type="text/javascript"></script>

<script type="text/javascript">
		
	//Set your ooid
	oo.setOOId("<?php echo $Settings->get('fm_analytics_ooid')->settingValue(); ?>");

	var aid 		= 	"<?php echo $Settings->get('fm_analytics_gaid')->settingValue(); ?>";
	var dateFrom 	= 	new Date($('#dateFrom').val());
	var dateTo 		= 	new Date($('#dateTo').val());
	
	//load reqs
	oo.load(function()
	{
		var dateFrom 	= 	new Date($('#dateFrom').val());
		var dateTo 		= 	new Date($('#dateTo').val());
		showStats(dateFrom, dateTo);
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

	$(function(){
		var dateFrom = new Date();
		var dateTo = new Date();
		//Set initial date ranges
		$('#dateFrom').val(dateFrom);
		$('#dateTo').val(dateTo);


		$('#dateForm').on('submit',function(){
			var df = $('#dateFrom').val();
			var dt = $('#dateTo').val();
			showStats(df,dt);
			return false;
		})
	})
</script>

<div class="widget">
  <h2>Experimental <?php echo $Lang->get('Site Stats'); ?></h2>
 	<form id="dateForm" method="post" action="#">
  		<fieldset id="daterange">
  			Date range: 
  			<input type="text" id="dateFrom" placeholder="mm/dd/yyyy"> - 
  			<input type="text" id="dateTo" placeholder="mm/dd/yyyy">
  			<input class="button" type="submit" value="Refresh">
  		</fieldset>
	</form>
	<div class="bd">
		<ul>
			<li><span id="pageviews"></span> Page Views</li>
			<li><span id="visitors"></span> Visitors</li>
			<li><span id="bounces"></span> Bounces</li>
		</ul>	
	</div>	
</div>