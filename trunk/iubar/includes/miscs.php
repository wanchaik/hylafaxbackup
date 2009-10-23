<?php


// VERSION 00.00.22

//error_reporting("E_ALL");
//ini_set("error_reporting","E_ALL");
ini_set("display_errors", "1");
error_reporting(E_ALL);


function booleanToString($b) {
	$result = "n.d.";
	if($b){
		$result = "si";
	}else{
		$result = "no";
	}
	return $result;
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


//############################################################## FILE


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

function getFilesFromDir($path){
		$narray = array();
		if (is_dir($path)) {
			$dh = opendir($path);
			if ($dh) {
				$i=0;
				while (($file = readdir($dh)) !== false) {
					//echo "filename: $file : filetype: " . filetype($path . $file) . "\n";
					$fullpath = $path . "/" . $file;
					if(is_file($fullpath)){
						if($file != '.' && $file != '..'){
							$narray[$i]=$file;
							$i++;
						}

					}else{
						//
					}

				} // end while

				closedir($dh);
			}
		}
		return $narray;
}

function getExtension($filename){
	$ext = "";
	$tokens = explode(".", $filename);
	$length = count($tokens);
	if($length>0){
		$ext = $tokens[$length-1];
	}
	return $ext;
}

function getDirSize($path) {
    $result = explode("\t",exec("du -hs ".$path),2);
    return ($result[1]==$path ? $result[0] : "error");
}


function getRemoteFileSize(){
	$remoteFile = 'http://us.php.net/get/php-5.2.10.tar.bz2/from/this/mirror';
	$ch = curl_init($remoteFile);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //not necessary unless the file redirects (like the PHP example we're using here)
	$data = curl_exec($ch);
	curl_close($ch);
	if ($data === false) {
	  echo 'cURL failed';
	  exit;
	}

	$contentLength = 'unknown';
	$status = 'unknown';
	if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', $data, $matches)) {
	  $status = (int)$matches[1];
	}
	if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
	  $contentLength = (int)$matches[1];
	}

	echo 'HTTP Status: ' . $status . "\n";
	echo 'Content-Length: ' . $contentLength;
}

function getFolderSize($path){ // old getDirSize function

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

//############################################################## MATH

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function filesize2bytes($str) {
    $bytes = 0;

    $bytes_array = array(
        'B' => 1,
        'KB' => 1024,
        'MB' => 1024 * 1024,
        'GB' => 1024 * 1024 * 1024,
        'TB' => 1024 * 1024 * 1024 * 1024,
        'PB' => 1024 * 1024 * 1024 * 1024 * 1024,
    );

    $bytes = floatval($str);

    if (preg_match('#([KMGTP]?B)$#si', $str, $matches) && !empty($bytes_array[$matches[1]])) {
        $bytes *= $bytes_array[$matches[1]];
    }

    $bytes = intval(round($bytes, 2));

    return $bytes;
}

function format_size($size) {

    $mod = 1024;

    $units = explode(' ','B KB MB GB TB PB');

    for ($i = 0; $size > $mod; $i++) {

        $size /= $mod;
    }

    return round($size, 2) . ' ' . $units[$i];

}

//############################################################## DATE & TIME


function getMonthNumFromFile($fullpath){
	$m = 0;
	$m = date("n", filemtime($fullpath));
	return $m;
}

function getDayMonthFromFile($fullpath){
	$m = 0;
	$m = date("j", filemtime($fullpath));
	return $m;
}

function getYearFromFile($fullpath){
	$y = 0;
	$y = date("Y", filemtime($fullpath));
	return $y;
}

function getMonthName($month){
	$name = "unknown";
    $timestamp = mktime(0, 0, 0, $month); // anno corrente, giorno corrente
    $name =  date("M", $timestamp);
	return $name;
}

function getFullMonthName($month){
	$name = "unknown";
    $timestamp = mktime(0, 0, 0, $month); // anno corrente, giorno corrente
    $name =  date("F", $timestamp);
	return $name;
}

function getMonthNum($date){
	$month = 0;
    $month =  date("n", $date);
	return $month;
}

function getYear($date){
	$month = 0;
    $month =  date("Y", $date);
	return $month;
}

function getNow(){
	//date_default_timezone_set('UTC');
	return date(getDateFormat());
}

function getDateFormat(){
	$format = DATE_RFC822;
	//$format = "F d Y H:i:s.";
	return $format;
}

function getLastMonths($n){
	$array = array();
	$today_time = time();
	$month_current = getMonthNum($today_time);
	$year_current = getYear($today_time);
	$bDone = false;
	$year = $year_current;

	$d2 = $month_current;
	$d3 = $n;

	while(!$bDone){
		$m_start = 0;
		$m_end = 0;
		$diff = $d2 - $d3;
		if($diff<=0){
			$m_start = 1;
			$m_end = $d2;
			$d2 = 12;
			$d3 = $diff * -1; // cambio il segno
		} else {
			$m_start = $diff;
			if($year == $year_current){
				$m_end = $month_current;
			}else{
				$m_end = 12;
			}
			$bDone = true;
		}
		$array["$year"] = array($m_start, $m_end);
		if($diff<=0){
			$year = $year - 1;
		}
	} // end while

	// Il passo successivo....

	$months = array();

	foreach ($array as $year=>$r){
		$array2 = array();
		$start = $r[0];
		$end = $r[1];
		for ($i = $start; $i <= $end; $i++) {
			$array2[] = $i;
		}
		$months["$year"] = $array2;
	}
	ksort($months);

	return $months;
}


//############################################################## DEBUG


function printDebug($txt){
	global $config, $brnl;
	if($config->debug){
		echo "<span class=\"debug_text\">" . $txt . "</span>" . $brnl;
	}
}

function printOutShell($cmd, $out){
	if($out==""){
		$out = "OK";
	}
	printOut("shell_exec " . $cmd . " --> " . $out, "A");
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

function printOut($txt, $option){
	global $config, $brnl;
	if($txt!=""){
		if($option=="A"){
			echo "<span class=\"debug_text\">Action: " . $txt . "</span>" . $brnl;
		}else if($option=="E"){
			echo "<span class=\"debug_text\">Error: " . $txt . "</span>" . $brnl;
		}else if($option=="W"){
			echo "<span class=\"debug_text\">Warning: " . $txt . "</span>" . $brnl;
		}else if($option=="R"){
			echo "<span class=\"cmd_out_text\">Output: " . $txt . "</span>" . $brnl;
		}else{
			printOut("Option $option is invalid", "E");
		}
	}
}

//############################################################## COMPRESSION

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


//############################################################## HTML

function initHeader($page_title){
	echo "<html>";
	echo "<header>";
	echo "<title>" . $page_title . "</title>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/iubar/iubar.css\" />";
	echo "</header>";
	echo "<body>";
}

function printHeader($page_title, $option=0){

	$index = "index.php";

	initHeader($page_title);

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

function printHeaderWithLogo($page_title, $app_name, $img, $option=0){

	$index = "index.php";

	initHeader($page_title);

	echo "<div class=\"container\">";

		echo "<div class=\"left-element\">";

		echo "<span id=\"applogo\" class=\"applogo\">";
		echo "<img alt=\"" . $app_name . "\" src=\"/iubar/img/" . $img . "\" /></span>";
		echo "<span id=\"appname\" class=\"appname\">";
		echo "$app_name</span>";

		echo "</div>";

		echo "<div class=\"right-element\">";
		if($option==1){
			echo "<a href=\"/index.php\">Vai al menu</a>";
		}
		if($option==1){
			echo "<a href=\"/" . $index . "\"><img alt=\"logo_iubar\" src=\"/iubar/img/logo.gif\" /></a>";
		}else{
			echo "<img alt=\"logo_iubar\" src=\"/iubar/img/logo.gif\" />";
		}
		echo "</div>";

	echo "</div>";
	// echo "<hr />";

	echo "<p></p>";

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

?>