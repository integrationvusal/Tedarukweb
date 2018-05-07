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
	
	
	
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Продажа'],
          ['2001',  10],
          ['2002',  120],
          ['2003',  70],
          ['2004',  410],
          ['2005',  351],
          ['2006',  1100],
          ['2007',  690],
          ['2008',  60],
          ['2009',  260],
          ['2010',  1060],
          ['2011',  620],
          ['2012',  130],
          ['2013',  10]
        ]);

        var options = {
          title: 'Общая сумма в AZN.',
		  hAxis: {title: 'Год', titleTextStyle: {color: 'blue'}}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
	
	<div id="chart_div" style="width: 100%; height: 450px;"></div>


        <!--<div ><p>all db table changing charset to utf-8</p>

            <?php
             $db_tableLaa = $pdo->query("SELECT CONCAT('ALTER TABLE `', t.`TABLE_SCHEMA`, '`.`', t.`TABLE_NAME`, '` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;') as sqlcode
  FROM `information_schema`.`TABLES` t
 WHERE 1
   AND t.`TABLE_SCHEMA` = 'siteman_amazon'
 ORDER BY 1")->fetchAll();

            echo '<pre>';

            foreach($db_tableLaa as $var)
            {
                echo $var[0].'<br />';
            };
            echo '</pre>';
            ?>

        </div>-->
	
				
	<div class="clear"></div>
	</div>
</div>