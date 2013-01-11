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

 #visitors {
 	float:left;
 	width:300px;
 	height:300px;
 	font-size:2em;
 }

 #pie {
 	float:left;
 	width:50%;
 	height:500px;
 }
</style>
<script src="/controlpanel/addons/apps/fm_analytics/js/oocharts.js" type="text/javascript"></script>


<script type="text/javascript">
		
			//Set your ooid
			oo.setOOId("<?php echo $Settings->get('fm_analytics_ooid')->settingValue(); ?>");

			var aid 		= 	"<?php echo $Settings->get('fm_analytics_gaid')->settingValue(); ?>";
			var dateFrom 	= 	"01/01/2013";
			var dateTo 		= 	"01/10/2013";
			
			//load reqs
			oo.load(function()
			{
				//Show date ranges
				$('#dateFrom').html(dateFrom);
				$('#dateTo').html(dateTo);



				//Create a new metric (aid, startDate, endDate)
				var metric = new oo.Metric(aid, new Date(dateFrom), new Date(dateTo));
				
				//Set the metric to pull from the visitor count
				metric.setMetric('ga:visitors');
				
				//draw in the h1 element with id 'met'
				metric.draw('met');



				//Create a new pie (aid, startDate, endDate)
				var p= new oo.Pie(aid, new Date(dateFrom), new Date(dateTo));
				
				//set the metric to pull from the visitor count
				p.setMetric('ga:visitors', 'Visits');
				
				//set the dimension to pull from the different browser types
				p.setDimension('ga:browser');
				
				//Set Google visualization options for slice colors
				p.setOption('colors', ['red', 'orange', 'blue', 'green']);
				
				//Set Google visualization option for chart title
				p.setOption('title', 'Browsers');
				
				//draw in the div element with id 'pie'
				p.draw('pie');



			});
		</script>

<div class="widget" style="width:100%">
  <h2>Experimental <?php echo $Lang->get('Site Stats'); ?></h2>
  
  <div id="daterange">Date range: <span id="dateFrom"></span> - <span id="dateTo"></span></div>
  <div class="bd">
		
  				<div id="visitors"><span id="met"></span> Visitors</div>
				<div id="pie"></div>

  </div>
</div>