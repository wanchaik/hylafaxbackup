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
	return $hostname;
}
function getWanIp(){
	$ip = $_SERVER["SERVER_ADDR"];
	return $ip;
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
	$array = explode("\r", $out);
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

?>
