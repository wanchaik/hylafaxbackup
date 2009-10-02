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
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, $value);" . $nl;
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
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, $value);" . $nl;
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
	$i=0;
	foreach ($array as $key => $value){
       	 echo "data.setValue($i, 0, '$key');" . $nl;
	     echo "data.setValue($i, 1, $value);" . $nl;
		 $i++;
	}
?>

        var options = {width: <?php echo $w; ?>, height: <?php echo $h; ?>, is3D: true, title: '<?php echo $title; ?>', colors: ['<?php echo $color; ?>']};

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
	     echo "data.setValue($i, 1, $value);" . $nl;
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

function printChart($div_name){
	echo "<div id=\"chart_div_" . $div_name . "\"></div>";
}


?>