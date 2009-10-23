<?php

function initBarChart($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;
?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["barchart"]});

      google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

      function drawChart_<?php echo $funct_name; ?>() {
        var data = new google.visualization.DataTable();

<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i = 0;
	foreach ($array as $key => $array2){
		echo "data.setValue($i, 0, '$key');" . $nl;
		$j = 1;
		foreach ($array2 as $value){
		    echo "data.setValue($i, $j, " . toValue($value) . ");" . $nl;
		 	$j++;
		}
		$i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, is3D: true, title: '<?php echo $title; ?>', colors: ['<?php echo $color; ?>']};

        var chart = new google.visualization.BarChart(document.getElementById('chart_div_<?php echo $div_name; ?>'));
        chart.draw(data, options);
      }
    </script>

<?php

}

function initColumnsChart($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;
?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["columnchart"]});

      google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

      function drawChart_<?php echo $funct_name; ?>() {
        var data = new google.visualization.DataTable();

<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i = 0;
	foreach ($array as $key => $array2){
		echo "data.setValue($i, 0, '$key');" . $nl;
		$j = 1;
		foreach ($array2 as $value){
		    echo "data.setValue($i, $j, " . toValue($value) . ");" . $nl;
		 	$j++;
		}
		$i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, is3D: true, title: '<?php echo $title; ?>', colors: ['<?php echo $color; ?>']};

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_<?php echo $div_name; ?>'));
        chart.draw(data, options);
      }
    </script>

<?php

}

function initAreaChart($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;
?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["areachart"]});

      google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

      function drawChart_<?php echo $funct_name; ?>() {
        var data = new google.visualization.DataTable();

<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i = 0;
	foreach ($array as $key => $array2){
		echo "data.setValue($i, 0, '$key');" . $nl;
		$j = 1;
		foreach ($array2 as $value){
		    echo "data.setValue($i, $j, " . toValue($value) . ");" . $nl;
		 	$j++;
		}
		$i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, legend: 'bottom', title: '<?php echo $title; ?>', colors: ['<?php echo $color; ?>']};

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div_<?php echo $div_name; ?>'));
        chart.draw(data, options);
      }
    </script>

<?php

}


function initPieChart($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;
?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['piechart']});

      google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

function drawChart_<?php echo $funct_name; ?>() {
  // Create and populate the data table.
  var data = new google.visualization.DataTable();

<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, " . toValue($value) . ");" . $nl;
		 $i++;
	}
?>

	var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, title: '<?php echo $title; ?>', is3D: true};
       var chart = new google.visualization.PieChart(document.getElementById('chart_div_<?php echo $div_name; ?>'));

	// Create and draw the visualization.
  	chart.draw(data, options);
}

</script>

<?php


}



function initLineChart($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;
?>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["linechart"]});

      google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

      function drawChart_<?php echo $funct_name; ?>() {
        var data = new google.visualization.DataTable();

<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, " . toValue($value) . ");" . $nl;
		 $i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, legend: 'bottom', title: '<?php echo $title; ?>', colors: ['<?php echo $color; ?>']};

        var chart = new google.visualization.LineChart(document.getElementById('chart_div_<?php echo $div_name; ?>'));
        chart.draw(data, options);
      }
    </script>

<?php

}



function printChart($div_name){
	echo "<div id=\"chart_div_" . $div_name . "\"></div>";
}



function initGauge($title, $funct_name, $div_name, $columns, $array, $color, $w, $h){
	global $nl;

?>

    <!--Load the AJAX API-->
    <script type='text/javascript' src='http://www.google.com/jsapi'></script>
    <script type='text/javascript'>

    google.load('visualization',  '1', {'packages': ['gauge'], 'language' : 'it'});

    // Set a callback to run when the API is loaded.
    google.setOnLoadCallback(drawChart_<?php echo $funct_name; ?>);

	function drawChart_<?php echo $funct_name; ?>() {
        var data = new google.visualization.DataTable();
<?php
	foreach ($columns as $key => $value){
       	 echo "data.addColumn('$value', '$key');" . $nl;
	}
    echo "data.addRows(" . count($array) . ");" . $nl;
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, " . toValue($value) . ");" . $nl;
		 $i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, title: '<?php echo $title; ?>'};
		// redFrom: 90, redTo: 100, yellowFrom:75, yellowTo: 90, minorTicks: 5
        var chart = new google.visualization.Gauge(document.getElementById('chart_div_<?php echo $div_name; ?>'));
        chart.draw(data, options);
     }

	</script>
<?php
}


function toValue($value){
	if($value!=""){
		$value = str_replace(",", ".", $value);
	}
	return $value;
}



////////////// EXAMPLE /////////////////////////


function printTable(){

  	global $nl;
  	$id = "table1";


?>

    <!--Load the AJAX API-->
    <script type='text/javascript' src='http://www.google.com/jsapi'></script>
    <script type='text/javascript'>

    google.load('visualization',  '1', {'packages': ['table'], 'language' : 'it'});

    // Set a callback to run when the API is loaded.
    google.setOnLoadCallback(drawTable_<?php echo $id; ?>);

	function drawTable_<?php echo $id; ?>() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('number', 'Salary');
        data.addColumn('boolean', 'Full Time Employee');
        data.addRows(4);
        data.setCell(0, 0, 'Mike');
        data.setCell(0, 1, 10000, '$10,000');
        data.setCell(0, 2, true);
        data.setCell(1, 0, 'Jim');
        data.setCell(1, 1, 8000, '$8,000');
        data.setCell(1, 2, false);
        data.setCell(2, 0, 'Alice');
        data.setCell(2, 1, 12500, '$12,500');
        data.setCell(2, 2, true);
        data.setCell(3, 0, 'Bob');
        data.setCell(3, 1, 7000, '$7,000');
        data.setCell(3, 2, true);

       var table = new google.visualization.Table(document.getElementById('<?php echo $id; ?>_div'));
       table.draw(data, {showRowNumber: true});
     }


	</script>
<?php
}


function printTableAndBar(){

  	global $nl;
  	$id1 = "tablesorted1";
  	$id2 = "barchartsorted1";


?>

    <!--Load the AJAX API-->
    <script type='text/javascript' src='http://www.google.com/jsapi'></script>
    <script type='text/javascript'>

    google.load('visualization',  '1', {'packages': ['barchart', 'table'], 'language' : 'it'});

    // Set a callback to run when the API is loaded.
    google.setOnLoadCallback(drawChart_<?php echo $id1; ?>);

	function drawChart_<?php echo $id1; ?>() {
      var data = new google.visualization.DataTable();
	  	data.addColumn('string', 'Name');
	  	data.addColumn('number', 'Salary');
	  	data.addColumn('boolean', 'Full Time');
	  	data.addRows(5);
	  	data.setCell(0, 0, 'John');
	  	data.setCell(0, 1, 10000);
	  	data.setCell(0, 2, true);
	  	data.setCell(1, 0, 'Mary');
	  	data.setCell(1, 1, 25000);
	  	data.setCell(1, 2, true);
	  	data.setCell(2, 0, 'Steve');
	  	data.setCell(2, 1, 8000);
	  	data.setCell(2, 2, false);
	  	data.setCell(3, 0, 'Ellen');
	  	data.setCell(3, 1, 20000);
	  	data.setCell(3, 2, true);  data.setCell(4, 0, 'Mike');
	  	data.setCell(4, 1, 12000);  data.setCell(4, 2, false);
	  	var formatter = new google.visualization.TableNumberFormat({prefix: '$'});
	  	formatter.format(data, 1); // Apply formatter to second column
	  	var view = new google.visualization.DataView(data);
	  	view.setColumns([0, 1]);
	  	var table = new google.visualization.Table(document.getElementById('<?php echo $id1; ?>_div'));
	  	table.draw(view);
	  	var chart = new google.visualization.BarChart(document.getElementById('<?php echo $id1; ?>_div'));
	  	chart.draw(view);
	  	google.visualization.events.addListener(table, 'sort',
	  		function(event) {
	  			data.sort([{column: event.column, desc: !event.ascending}]);
	  			chart.draw(view);
		});

     }


	</script>
<?php
}

?>