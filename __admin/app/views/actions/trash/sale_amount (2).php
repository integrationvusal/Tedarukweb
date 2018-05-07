<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');
		
?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Статистика продаж</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
	
	
	<!--Date interval start-->
	<div class="date_interval_wrapper">
	  <form name="exact_date_form" action="" method="" class="date_form">
	    <span>Показать статистику на: </span>
		<input type="text" name="exact_date" class="date_input datepicker"/>
		<input type="submit" name="show_chart_date" value="Показать" />
	  </form>
	  
	  <form name="exact_date_form" action="" method="" class="date_form"  style="margin-left:30px;">
	    <span>Показать статистику с: </span>
		<input type="text" name="exact_date" class="date_input datepicker" />
		<span>по: </span>
		<input type="text" name="exact_date" class="date_input datepicker" />
		<input type="submit" name="show_chart_date_interval" value="Показать"  />
	  </form>
	  
	<div class="clear"></div>
	</div>
	
	
	<!--Date interval end-->
	<script type="text/javascript" language="javascript">
	 $('#datepicker').blur(function(){
	   //alert('pouuu');
	 });
	</script>
	
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	
	
	
	
	
	
	
	
	
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Продажа'],
          ['2004',  1000],
          ['2005',  1170],
          ['2006',  660],
          ['2007',  403],
          ['2008',  510],
          ['2009',  330],
          ['2010',  830],
          ['2011',  1100],
          ['2012',  320],
          ['2013',  230]
        ]);

        var options = {
          title: 'Количество проданного товара',
          hAxis: {title: 'Год', titleTextStyle: {color: 'blue'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
    </script>	
	<div id="chart_div1" style="width: 100%; height: 450px;"></div>

				
	<div class="clear"></div>
	</div>
</div>
	
	