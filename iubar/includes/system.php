<?php


function getWebServerVersion(){
	$sw = $_SERVER["SERVER_SOFTWARE"];
	return $sw;
}

function getLinuxVersion(){

	$ver = "unknown";
	$cmd = "uname -sr";
	$out = shell_exec($cmd);
	$cmd2 = "cat /etc/*elease*";
	$out2 = shell_exec($cmd2);
	$ver = $out . " - " . $out2;

	//$cmd3 = "head -n1 /etc/issue";
	//$out3 = shell_exec($cmd);

	return $ver;
}

function getPathFromFile($fullfilename){
	//$path = "/home/httpd/html/index.php";
	//$file = basename($path);         // $file is set to "index.php"
	//$file = basename($path, ".php"); // $file is set to "index"
	$path = dirname($fullfilename);
	return $path;
}

function getLanIp(){
	$ip = $_SERVER["SERVER_ADDR"];
	return $ip;
}

function getHostName(){
	$hostname = $_SERVER["SERVER_NAME"];
	// $ip = $_SERVER["SERVER_NAME"]
	//$hostname = gethostbyaddr($ip) . " - " . gethostbyname($ip);
	return $hostname;
}

function getHostName2(){
	$cmd = "hostname";
	$hostname = shell_exec($cmd);
	return $hostname;
}

function getServerAddr(){
	$ip = $_SERVER["SERVER_ADDR"];
	return $ip;
}

function getGatewayIp(){
	$ip = "unknown";
	$cmd = "ip route show";
	$out = shell_exec($cmd);
	$find1 = "default via ";
	$find2 = " dev";
	$pos = strpos($out, $find1);
	if ($pos !== false) {
		 // echo "The string '$findme' was found in the string '$mystring' and exists at position $pos";
		$start = $pos + strlen($find1);
		$end = strrpos($out, $find2);
		$length = $end - $start;
		$ip = substr($out, $start,  $length);
		// echo "start: $start end: $end length: $length id: $id";
	} else {
		$ip = "Unknown (the string '$find1' was not found in the string '$line')" . "<br/>";
	}
	return $ip;
}

function getESSID(){
	$id = "unknown";
	$cmd = "iwconfig wlan0";
	$out = shell_exec($cmd);
	$find = "ESSID:\"";
	$pos = strpos($out, $find);
	if ($pos !== false) {
		 // echo "The string '$findme' was found in the string '$mystring' and exists at position $pos";
		$start = $pos + strlen($find);
		$end = strrpos($out, "\"");
		$length = $end - $start;
		$id = substr($out, $start,  $length);
		// echo "start: $start end: $end length: $length id: $id";
	} else {
		// echo "(the string '$findme' was not found in the string '$mystring')";
	}
	return $id;
}

function getIpAddr($nic){
	$ip = "unknown";
	$cmd = "ip addr show | grep " . $nic;
	$out = shell_exec($cmd);
	$array = explode("\n", $out);
	$line = $array[1];
	$find1 = "inet ";
	$find2 = " brd";
	$pos = strpos($line, $find1);
	if ($pos !== false) {
		 // echo "The string '$findme' was found in the string '$mystring' and exists at position $pos";
		$start = $pos + strlen($find1);
		$end = strrpos($line, $find2);
		$length = $end - $start;
		$ip = substr($line, $start,  $length);
		// echo "start: $start end: $end length: $length id: $id";
	} else {
		$ip = "Unknown (the string '$find1' was not found in the string '$line')" . "<br/>";
	}
	return $ip;
}

function getLanIpAddr(){
	return getIpAddr("eth0");
}

function getWLanIpAddr(){
	return getIpAddr("wlan0");
}

function getWanIp(){
	$url = "http://www.iubar.it/tools/ip.php";
	$content = file_get_contents($url);
	return $content;
}

function getDns(){
	$ip = "";
	$cmd = "cat /etc/resolv.conf";
	$out = shell_exec($cmd);
	$array = explode("\n", $out);
	foreach($array as $record){
		$pos = strpos($record, "nameserver");
		if ($pos !== false) {
			$array2 = explode(" ", $record);
			$last = count($array2) - 1;
			if($ip!=""){
				$ip = $ip . " - ";
			}
			$ip = $ip . $array2[$last];
		}
	}
	return $ip;
}


function seeYou(){
	// USAGE: //echo "Your Computer : ".seeyou();
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return gethostbyaddr($ip);
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

function getCpuModel(){
	$cmd = "grep \"model name\" /proc/cpuinfo";
	$out = shell_exec($cmd);
	$array = explode("\n", $out);
	$line = $array[0];
	return $line;
}

function getLastTimeReboot(){
	$cmd = "last reboot";
	$out = shell_exec($cmd);
	$array = explode("\n", $out);
	$first_line = trim($array[0]);
	$array2 = explode(" ", $first_line);
	$index = count($array2);
	//$a = $index-4;
	//$b = $index-3;
	//$c = $index-2;
	//$d = $index-1;
	//$d = $array2[$a] . " " . $array2[$b] . " " . $array2[$c];// . " " . $array2[$index-1];
	return $first_line;
}

function searchAndReplace($file, $pattern, $replacement){
	global $brnl;
	if($pattern!=""){
		if(!file_exists($file)) { // if file doesn't exist...
			echo "The file $file doesn't seem to exist." . $brnl; // ...stop executing code.
		} else { // if file exists...
			if(is_writable($file)){
				$f = file($file); // ...make new variable...
				$content = ""; // ...and another...
				$replacement2 = $pattern . "\t" . $replacement . "\n";
				for($i = 0; $i < count($f); $i++) { // ...run through the loop...
					$pos = strpos($f[$i], $pattern);
					if ($pos !== false) {
						$content .= $replacement2;
						//print "Line replaced!!!";
					} else { // the
						$content .= $f[$i]; // content.
					}
				} // end for

				if($content!=""){
					$fi = fopen($file, "w"); // open specified file...
					fwrite($fi, $content); // and rewrite it's content.
					fclose($fi); // close file.
				} // end if
			} else	{
				echo "The file $file doesn't seem to be writable." . $brnl; // ...stop executing code.
			}
		}
	} else {
		echo "pattern to find is empty"  . $brnl;
	}
} // end function


function countFilesInDir($directory) {

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // keep going until all files in directory have been read
    while ($file = readdir($handler)) {

        // if $file isn't this directory or its parent,
        // add it to the results array
        if ($file != '.' && $file != '..')
            $results[] = $file;
    }

    // tidy up: close the handler
    closedir($handler);

    // done!
    //return $results;

    $n = count($results);
    return $n;
}


?>
