<?php

;


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

function getESSID($wlan){
	$id = "unknown";
	$cmd = "iwconfig " . $wlan;
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

function getWanIp(){
	$url = "http://www.iubar.it/tools/ip.php";
	//ini_set("default_socket_timeout", 2);
	$opts = array('http' => array('timeout' => 2)); // timeout 2 second
	$ctx = stream_context_create($opts);
	$content = file_get_contents($url, false, $ctx);

	return $content;
}

function getDnsServer($n){
	global $brnl;
	$dns = "";
	$file = "/etc/resolv.conf";
	$cmd = "cat $file";
	$out = shell_exec($cmd);
	$array = explode("\n", $out);
	$found = 0;
	foreach($array as $record){
		$pos = strpos($record, "nameserver");
		if ($pos !== false) {
			$found++;
			$array2 = explode(" ", $record);
			$last = count($array2) - 1;
			if($found==$n){
				$dns = $array2[$last];
			}
		}
	}
	return $dns;
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

function searchAndReplace($file, $pattern, $replacement, $replace_null){
	global $brnl;
	$b = false;
	if($pattern!=""){
		if(!file_exists($file)) { // if file doesn't exist...
			echo "The file $file doesn't seem to exist." . $brnl; // ...stop executing code.
		} else { // if file exists...
			if(is_writable($file)){
				$f = file($file); // ...make new variable...

				if($f){

					$content = ""; // ...and another...

					$replacement2 = "";
					$j = 0;
					if (is_array($replacement)){
						$replacement2 = $replacement[$j] . "\n";
					}else{
						$replacement2 = $replacement . "\n";
					}

					for($i = 0; $i < count($f); $i++) { // ...run through the loop...
						$pos = strpos($f[$i], $pattern);
						if ( ($pos !== false) && ( (strlen($replacement2)>1) || ($replace_null) ) ) {

							$content .= $replacement2;
							$b = true;
							//print "Line replaced!!!";
							if (is_array($replacement)){
								$j++;
								$replacement2 = $replacement[$j] . "\n";
							}

						} else { // the
							$content .= $f[$i]; // content.
						}
					} // end for

					if($content!=""){
						$fi = fopen($file, "w"); // open specified file...
						fwrite($fi, $content); // and rewrite it's content.
						fclose($fi); // close file.
					} // end if

				}

			} else	{
				echo "The file $file doesn't seem to be writable." . $brnl; // ...stop executing code.
			}
		}
	} else {
		echo "pattern to find is empty"  . $brnl;
	}
	return $b;
} // end function


function searchAndReplace2($file, $pattern, $replacement){
	global $brnl;
	$b2 = false;
	$b = searchAndReplace($file, $pattern, $replacement, true);
	// Se non trovo nulla, allora aggiungo in coda
	if(!$b){
		if (is_array($replacement)){
			for($i = 0; $i < count($replacement); $i++){
				$replacement2 = $replacement[$j] . "\n";
				$b2 = append_file($file, $replacement2);
			}
		}else{
			$replacement2 = $replacement . "\n";
			$b2 = append_file($file, $replacement2);
		}
		if(!$b2){
			echo "The file $file is NOT writable" . $brnl;
		}
	}
}

   function write_file($filename,$newdata) {
          $f=fopen($filename,"w");
          fwrite($f,$newdata);
          fclose($f);
   }

function append_file($filename, $newdata) {
	$b = false;
	if(is_writable($filename)){
		$f=fopen($filename,"a");
		fwrite($f,$newdata);
		fclose($f);
		$b = true;
	}
	return $b;
}

   function read_file($filename) {
          $f=fopen($filename,"r");
          $data=fread($f,filesize($filename));
          fclose($f);
          return $data;
   }

function countFilesInDir($directory) {
	$n = 0;
    // create an array to hold directory list
    $results = array();

	if(is_readable($directory)){
		// create a handler for the directory
		$handler = opendir($directory);
		if($handler){
			// keep going until all files in directory have been read
			while ($file = readdir($handler)) {

				// if $file isn't this directory or its parent,
				// add it to the results array
				if ($file != '.' && $file != '..')
					$results[] = $file;
			}

			// tidy up: close the handler
			closedir($handler);
		}
		// done!
		//return $results;
		$n = count($results);
	}else{
		$n = -1;
	}


    return $n;
}

function getFirstValue($file, $pattern, $del_chars = array("\"", "'"), $end_char="\n") {
	$value = "null";
	$values = getValue($file, $pattern, $del_chars, $end_char);
	$n = count($values);
	if($n>0){
		$value = $values[0];
	}
	return $value;
}

function getLastValue($file, $pattern, $del_chars = array("\"", "'"), $end_char="\n") {
	$value = "null";
	$values = getValue($file, $pattern, $del_chars, $end_char);
	$n = count($values);
	if($n>0){
		$value = $values[($n-1)];
	}
	return $value;
}

function getValue($file, $pattern, $del_chars=array("\"", "'"), $end_char="\n") {
	global $brnl;
	$values = array();
	if($file!=""){
		if(file_exists($file)){
			$bReadable = false;
			if (is_readable($file)) {
					//echo "File accessibile" . $nl;
					$bReadable = true;
			}else{
					$txt = "The file " . $file . " is NOT readable";
					echo $txt . $brnl;
			}

			if($bReadable){
				$handle = @fopen($file, "r");
				if ($handle) {
					while (!feof($handle)) {
						$buffer = fgets($handle, 4096);
						$pos = strpos($buffer, $pattern);
						if ($pos !== false) {
							$start = $pos + strlen($pattern);
							$end = strrpos($buffer, $end_char, $start);
							if ($pos === false) { // note: three equal signs
								$end = strrpos($buffer, "\n", $start);
							}
							$length = $end - $start;
							$value = substr($buffer, $start,  $length);
							$value = trim($value);
							foreach($del_chars as $char){
								$value = str_replace($char, "", $value);
							}
							$value = trim($value);
							$values[] = $value;
						}

					}
					fclose($handle);
				}
			} // end if
		} else { // end if
				$txt = "The file " . $file . " does NOT exist";
				echo $txt . $brnl;
		}
	} // end if
	return $values;
} // end function


function is_connected() {
	//ini_set("default_socket_timeout", 3);
    $connected = fsockopen("www.google.com", 80, $errno, $errstr, 2); // timeout 2 sec
    if ($connected){
        $is_conn = true;
        fclose($connected);
    }else{
        $is_conn = false;
    }
    return $is_conn;

}//end is_connected function


function boolToString($n){
	if($n>0){
		$txt = "Yes";
	}else{
		$txt = "No";
	}
	return $txt;
}

function getWlanSignals(){
	$r = array();
	$cmd = "iwlist scan";
	$out = shell_exec($cmd);
	$r = getValueFromOutput($out, "Signal level=");
	return $r;
}

function getWlanNetworks(){
	$r = array();
	$cmd = "iwlist scan";
	$out = shell_exec($cmd);
	$r = getValueFromOutput($out, "ESSID:");
	return $r;
}

function getValueFromOutput($out, $pattern){
	$values = array();
	$array=explode("\n", $out);
	foreach($array as $line){
		$pos = strpos($line, $pattern);
		if ($pos !== false) {
			$start = $pos + strlen($pattern);
			$end = strlen($line);
			$length = $end - $start;
			$value = substr($line, $start,  $length);
			$value = trim($value);
			$value = str_replace("\"", "", $value);
			$value = str_replace("'", "", $value);
			$values[] = $value;
		}
	}
	return $values;
}



function printArray2($array){
	$txt = "";
	$i = 0;
	foreach($array as $a){
		if($i==0){
			$txt = $a;
		}else{
			$txt = $txt . " - " . $a;
		}
		$i++;
	}
	return $txt;
}

function checkDns(){
	$b = false;
	$ip = "";

	//$ip = gethostbyname("php.net");

	$ip = getAddrByHost("php.net");
	if($ip!=""){
		$b=true;
	}

	/*
	$result = dns_get_record("php.net");
	if(sizeof($result)>0){
		$index = sizeof($result) - 1;
		//print_r($result[$index]);
		$record1 = $result[$index];
		if (isset($record1["ip"])){
			$ip = $record1["ip"];
			if($ip!=""){
				$b = true;
			}
		}
	}
	*/

	return $b;
}

function getAddrByHost($host, $timeout = 1) { // posso impostare il timeout a differenza di gethostbyname
   $query = `nslookup -timeout=$timeout -retry=1 $host`;
   if(preg_match('/\nAddress: (.*)\n/', $query, $matches)){
      return trim($matches[1]);
   }
   return "";
}

function shutdown(){
	global $brnl;
	$cmd = "sudo shutdown -h now";

	$last_line = system($cmd, $retval); // in alternativa exec($command, $output);

	//echo "<p>";
	//echo "Last line of the output: " . $last_line . $brnl;
	//echo "Return value: " . $retval . $brnl;
	//echo "</p>";

	return $retval;
}

function reboot(){
	global $brnl;
	$cmd = "sudo reboot";
	$cmd2 = "shutdown -r now";

	$last_line = system($cmd, $retval); // in alternativa exec($command, $output);

	//echo "<p>";
	//echo "Last line of the output: " . $last_line . $brnl;
	//echo "Return value: " . $retval . $brnl;
	//echo "</p>";

	return $retval;

}


?>
