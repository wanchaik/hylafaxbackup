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
	return $ver;
}

function getPathFromFile($fullfilename){
	//$file = basename($fullfilename);
	$dir = dirname($fullfilename);
	return $dir;
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


?>