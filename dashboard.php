<?php

$Lang  = $API->get('Lang');

$Settings = $API->get('Settings');
?>


<style type="text/css">
	#daterange {
		font-size:1.5em;
		width:100%;
		padding:1em 0;
	}

 .fm_analytics-block {
 	float:left;
 	width:300px;
 	height:300px;
 	font-size:2em;
 }

 
</style>
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


				var p= new oo.Pie(aid, dateFrom, dateTo);
				p.setMetric('ga:visitors', 'Visits');
				p.setDimension('ga:browser');
				p.setOption('colors', ['red', 'orange', 'blue', 'green']);
				p.setOption('title', 'Browsers');
				p.draw('pie');



				var timeline = new oo.Timeline(aid, dateFrom, dateTo);
				timeline.addMetric('ga:visitors', 'Visits');
				timeline.addMetric('ga:newVisits', 'New Visits');
				timeline.draw('timeline');

			}

			$(function(){
				//Show date ranges
				$('#dateFrom').html(dateFrom);
				$('#dateTo').html(dateTo);


				$('#dateForm').on('submit',function(){
					var df = $('#dateFrom').val();
					var dt = $('#dateTo').val();
					showStats(df,dt);
					return false;
				})
			})
		</script>

<div class="widget" style="width:100%">
  <h2>Experimental <?php echo $Lang->get('Site Stats'); ?></h2>
 <form id="dateForm" method="post" action="#">
  <div id="daterange">Date range (mm/dd/yyyy): <input type="text" id="dateFrom" value="01/01/2013"> - <input type="text" id="dateTo" value="01/11/2013"></span> <input type="submit" value="Refresh"></div>
</form>
  <div class="bd">
		
			<div class="fm_analytics-block">
  				<div><span id="pageviews"></span> Page Views</div>
  				<div><span id="visitors"></span> Visitors</div>
  				<div><span id="bounces"></span> Bounces</div>
  			</div>

  			<div class="fm_analytics-block">
				<div id="pie"></div>
			</div>



			<div class="fm_analytics-block">
				<div id="timeline"></div>
			</div>
  </div>
</div>