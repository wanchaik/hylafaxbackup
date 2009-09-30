<?php

//error_reporting("E_ALL");
//ini_set("error_reporting","E_ALL");
ini_set("display_errors", "1");
error_reporting(E_ALL);

function printHeader($title, $option=0){


	$index = "index.php";


	echo "<html>";
	echo "<header>";
	echo "<title>" . $title . "</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/iubar/iubar.css\" />";
	echo "</header>";
	echo "<body>";

	echo "<div class=\"container\">";

	echo "<div class=\"left-element\">";
	if($option==1){
		echo "<a href=\"/" . $index . "\"><img alt=\"logo_iubar\" src=\"/iubar/img/logo.gif\" /></a>";
	}else{
		echo "<img alt=\"logo_iubar\" src=\"/iubar/img/logo.gif\" />";
	}
	echo "</div>";

	echo "<div class=\"right-element\">";
	if($option==1){
		echo "<a href=\"/index.php\">Vai al menu</a>";
	}
	echo "</div>";

	echo "</div>";
	// echo "<hr />";
	echo "<p> </p>";

}


function printFooter2($label, $link){
	echo "<hr />";
	echo "<div align=\"right\"><small><a href=\"" . $link . "\">$label</a></small></div>";
	echo "</body>";
	echo "</html>";
}

function printFooter(){
	echo "<hr />";
	echo "<div align=\"right\"><small>www.iubar.it</small></div>";
	echo "</body>";
	echo "</html>";
}


function foldersize($path) {

    $total_size = 0;
    $files = scandir($path);


    foreach($files as $t) {
   	 //echo "Analizzo: " . $t . "<br/>";
        if (is_dir(rtrim($path, '/') . '/' . $t)) {

            if ($t != "." && $t != "..") {

                $size = foldersize(rtrim($path, '/') . '/' . $t);
		  //echo "size: " . $size . "<br/>";
                $total_size += $size;
            }
        } else {

            $size = filesize(rtrim($path, '/') . '/' . $t);
	     //echo "size: " . $size . "<br/>";
            $total_size += $size;
        }

    }

    return $total_size;
}

function format_size($size) {

    $mod = 1024;

    $units = explode(' ','B KB MB GB TB PB');

    for ($i = 0; $size > $mod; $i++) {

        $size /= $mod;
    }

    return round($size, 2) . ' ' . $units[$i];

}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function booleanToString($b) {
	$result = "n.d.";
	if($b){
		$result = "si";
	}else{
		$result = "no";
	}
	return $result;
}


function printZipInfo($archive){
	$br = "<br />";
	$za = new ZipArchive();

	$za->open($archive);
	print_r($za);
	var_dump($za);
	echo "numFiles: " . $za->numFiles . $br;
	echo "status: " . $za->status  . $br;
	echo "statusSys: " . $za->statusSys . $br;
	echo "filename: " . $za->filename . $br;
	echo "comment: " . $za->comment . $br;

	for ($i=0; $i<$za->numFiles;$i++) {
	   echo "index: $i" . $br;
	   print_r($za->statIndex($i));
	}
	echo "numFile:" . $za->numFiles . $br;
}

function isFileOk($file){
$nl = "<br />";
$result = false;
$b1 = false;
$b2 = false;

if (file_exists($file)){
        //echo "Il file esiste" . $nl;
	$b1 = true;
}else{
        echo "Il file " . $file . " NON esiste" . $nl;
}

if (is_readable($file)) {
        //echo "File accessibile" . $nl;
	$b2 = true;
}else{
        echo "File " . $file . " NON accessibile" . $nl;
}

$result = $b1 && $b2;
return $result;
}

function clearDirectory($path){
	if(strlen($path)>1){
		$cmd = "rm -rf " . $path;
  		$result = shell_exec($cmd);
		if(strlen($result)>0){
			$result = $result . " (comando: " . $cmd . ")";
		}
	}
	return $result;
}

function checkVersion($v1, $v2){
	// formato xx.xx.xx
	$r = 0;
	$right1 = ($v1[7] / 10000) +  ($v1[6] / 1000) + ($v1[4] / 100) +  ($v1[3] / 10);
	$right2 = ($v2[7] / 10000) +  ($v2[6] / 1000) + ($v2[4] / 100) +  ($v2[3] / 10);
	$left1 = ($v1[0] * 10) + $v1[1];
	$left2 = ($v2[0] * 10) + $v2[1];

	$ver1 = $left1 + $right1;
	$ver2 = $left2 + $right2;

	if(($ver1=="") && ($ver2>0)){
		$r = 1;
	}

	if( ($ver1<$ver2) && ($ver1>0) && ($ver2>0) ) {
		$r = 1;
	}
	return $r;
}

function needUpdate(){
	include("version.php");
	$b = false;
	//echo "gui_version: " . $gui_version; // test
	$b1 = checkVersion($gui_version, $gui_last_version);
	$b2 = checkVersion($list_version, $list_last_version);
	$b = $b1 || $b2;
	return $b;
}


function copy_directory( $source, $destination ) {
	if ( is_dir( $source ) ) {
		@mkdir( $destination );
		$directory = dir( $source );
		while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
			if ( $readdirectory == '.' || $readdirectory == '..' ) {
				continue;
			}
			$PathDir = $source . '/' . $readdirectory;
			if ( is_dir( $PathDir ) ) {
				copy_directory( $PathDir, $destination . '/' . $readdirectory );
				continue;
			}
			copy( $PathDir, $destination . '/' . $readdirectory );
		}

		$directory->close();
	}else {
		copy( $source, $destination );
	}
}

function writeArray2File($linesArray, $filename, $nl){
	$result = "";
	$b = isFileOk($filename);
	if($b){

		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {

		    if (!$handle = fopen($filename, 'w+')) {
			 $result = "Cannot open file ($filename)";
			 exit;
		    }

		    foreach ($linesArray as $line_num => $line) {
			 //echo "writing..." . $line . "<br/>";
		   	 $b2 = fwrite($handle, ($line . $nl));

		   	 if ($b2 === FALSE) {
				$result = "Cannot write to file ($filename)";
				return $result;
			 }

		    }

		    fclose($handle);

		} else {
		    $result = "The file $filename is not writable";
		}

	}else{
		$result = "...cannot write file " . $filename . "...";
	}
	return $result;
}

function printAssociativeArray($array){
	$br = "<br />";
	foreach ($array as $key => $value){
		echo $key . " => " . $value . $br;
	}
}

function printArray($array){
	$br = "<br />";
	for($i=0; $i < count($array); $i++){
		echo $i . " => " . $array[$i] . $br;
	}
}

function getUrl($url){
	$url2 = $url;
	$pos = strpos($url, "?");
	if($pos>0){
		$url2 = substr($url, 0 , $pos);
	}

	$last = $url2[strlen($url2)-1];
	if($last=="/"){
		$url2 = substr($url2, 0, strlen($url2)-1);
	}

	return $url2;
}

function getHostFromUrl($url){
	$array = parse_url($url);
	// $url['scheme'] = http
	// $url['host'] = www.php.net
	// $url['path'] = /download-php.php3
	// $url['query'] = csel=br
	$host = $array['host'];
	//$host = str_replace("www.", "", $host);
	return $host;
}

function csv2array2($filename, $limit=0, $sep=" "){
	$row = 0;
	$array = array();
	$b = isFileOk($filename);
	if($b){
	$handle = fopen($filename, "r");
	while (($data = fgetcsv($handle, $limit, $sep)) !== FALSE) {
	    $array[$row] = $data;
	    $row++;
	}
	fclose($handle);
	}
	return $array;
}

function getAdminPassword(){
	$str = "";
	$pattern = "\$adminpassword";
	$str = readConfig($pattern);
	return $str;
}

function getBypassPassword(){
	$str = "";
	$pattern = "\$bypasspassword";
	$str = readConfig($pattern);
	return $str;
}

function isOnlyWarning(){
	$str = "";
	$pattern = "\$onlywarning";
	$str = readConfig($pattern);
	return $str;
}

function canByPass(){
	$str = "";
	$pattern = "\$canbypass";
	$str = readConfig($pattern);
	return $str;
}

function bool2text($b){
	$txt = "no";
	if($b){
		$txt = "yes";
	}
	return $txt;
}

function getScriptPath(){
	$path = "/";
	$scriptname = $_SERVER["SCRIPT_FILENAME"];
	$pos = strrpos($scriptname, "/");
	if($pos!==FALSE){
		$path = substr($scriptname, 0, $pos+1);
	}
	return $path;
}

function readConfig($pattern){
	$str = "";
	$path = getScriptPath();
	$file = "/var/www/html/iubar/config.php";
	$linesArray = file($file);
	foreach ($linesArray as $line_num => $line) {
	       //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
		$pos = strpos($line, $pattern);
		if($pos!==FALSE){
			//$line = trim($line);
			$start = (strlen($pattern)) + 2; // due caratteri ovvero "=
			$end = strlen($line) - $start - 3; // due caratteri ovvero ";\n
			$str = substr($line, $start, $end);
			//echo "Substr: " . $start . ", " . $end . "<br />\n";
			//echo "String: " . $str . "<br />\n";
			//echo "Line: " . $line . "<br />\n";
		}
	}
	return $str;
}

function changeAdminPassword($pass){
	$pattern = "\$adminpassword";
	$b = writeConfig($pattern, $pass);
	return $b;
}

function changeBypassPassword($pass){
	$pattern = "\$bypasspassword";
	$b = writeConfig($pattern, $pass);
	return $b;
}

function writeConfig($pattern, $value){

	$b = false;
	$file = "../config.php";
	$str = $pattern . "=\"" . $value . "\";\n";
	$num_line_to_change = -1;
	$linesArray = file($file);
	foreach ($linesArray as $line_num => $line) {
		$pos = strpos($line, $pattern);
		if($pos!==FALSE){
			$num_line_to_change = $line_num;
		}
 	}

	if($num_line_to_change>-1){
		$linesArray[$num_line_to_change] = $str;
	}

	// Open the file
	$handle = fopen($file, 'w+');

	// Write the text into it
	//$b = fwrite($handle, $linesArray); // è possibile solo se si utilizza la funzione explode o serialize

	$numElements = count($linesArray);
	for($i = 0; $i < $numElements; $i++) {
		fwrite($handle, $linesArray[$i]);
	}
	fclose($handle);

	return true;

}


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

function printChart($funct_name){
	echo "<div id=\"chart_div_" . $funct_name . "\"></div>";
}

?>