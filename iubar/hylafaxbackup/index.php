<?php

include_once("../includes/miscs.php");
include_once("../includes/google.php");
include_once("app.php");
include_once("../includes/version.php");
include_once("../includes/ftp.php");
include_once("../includes/system.php");
include_once("config.php");
include_once("hylafax_errors.php");

ini_set("display_errors", "1");
error_reporting(E_ALL);

$br = "<br/>";
$brnl = $br . "\r\n";

$config = new config();
$app = new app();

printHeaderWithLogo($app->project_name, $app->project_name, "hd2-backup.png");

echo "<hr />";

$action = "default";
if(isset($_REQUEST["action"])){
	$action = $_REQUEST["action"];
}

if($action == "saveconfig"){
	saveconfig();
	//printOut("Config saved.", "A");
} else if($action == "config"){
	printConfig();
} else if($action == "update"){
	printUpdate();
} else if($action == "stats"){
	printStats();
} else if($action == "logs"){
	printLogs();
} else if($action == "errorstats"){
	printErrorStats();
} else if($action == "operations"){
	printMenu2();
} else if($action == "backup"){
	backup();
	printOut("Backup done.", "A");
} else if($action == "restore"){
	restore();
	printOut("Restore done.", "A");
} else if($action == "support"){
	printSponsor();
} else if($action == "main"){
	printMain();
} else if($action == "chmod"){
	$out = chmodLogFiles();
	echo "<p>Hylafax log files permissions changed. Now you can read the hylafax log files from this web application</p>";
} else {
	printMain();
}

printMenu();

printFooter2($app->project_name . " (ver " . $app->version . ")", $app->project_home);

// #################################################################


function toBoolean($str){
	$n = 0;
	if(trim($str)=="on"){
		$n = 1;
	}
	return $n;
}

function saveconfig() {
	global $brnl;

	$file = "config.php";

	//printOut("Function not ready in this version, please edit manually the file config.php", "W");


	$pattern1 = "\$hylafax_fax_backup";
	$pattern2 = "\$hylafax_config_backup";

	$pattern3 = "\$avantfax_config_backup";
	$pattern4 = "\$avantfax_fax_backup";
	$pattern5 = "\$avantfax_db_dump";

	$pattern6 = "\$use_ftp";

	// FTP CONFIG

	$pattern7 = "\$ftp_host";
	$pattern8 = "\$ftp_user";
	$pattern9 = "\$ftp_pass";
	$pattern10 = "\$ftp_file_fax";
	$pattern11 = "\$ftp_file_config";
	$pattern12 = "\$ftp_dir";

	// FILE SYSTEM CONFIG

	$pattern13 = "\$fs_remote_file_fax";
	$pattern14 = "\$fs_remote_file_config";

	if(isset($_REQUEST["hylafax_fax_backup"])){ // OK
		$value = toBoolean($_REQUEST["hylafax_fax_backup"]);
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern1 . " = " . $value . ";";
		searchAndReplace2($file, $pattern1, $line);
	}
	if(isset($_REQUEST["hylafax_config_backup"])){ // OK
		$value = toBoolean($_REQUEST["hylafax_config_backup"]);
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern2 . " = " . $value . ";";
		searchAndReplace2($file, $pattern2, $line);
	}
	if(isset($_REQUEST["avantfax_config_backup"])){ // OK
		$value = toBoolean($_REQUEST["avantfax_config_backup"]);
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern3 . " = " . $value . ";";
		searchAndReplace2($file, $pattern3, $line);
	}
	if(isset($_REQUEST["avantfax_fax_backup"])){ // OK
		$value = toBoolean($_REQUEST["avantfax_fax_backup"]);
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern4 . " = " . $value . ";";
		searchAndReplace2($file, $pattern4, $line);
	}
	if(isset($_REQUEST["avantfax_db_dump"])){ // OK
		$value = toBoolean($_REQUEST["avantfax_db_dump"]);
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern5 . " = " . $value . ";";
		searchAndReplace2($file, $pattern5, $line);
	}
	if(isset($_REQUEST["group1"])){ // OK
		$value = $_REQUEST["group1"];
		if($value=="ftp"){
			$value = 1;
		}else{
			$value = 0;
		}
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern6 . " = " . $value . ";";
		searchAndReplace2($file, $pattern6, $line);
	}
	if(isset($_REQUEST["ftp_host"])){ // OK
		$value = $_REQUEST["ftp_host"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern7 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern7, $line);
	}
	if(isset($_REQUEST["ftp_user"])){ // OK
		$value = $_REQUEST["ftp_user"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern8 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern8, $line);
	}
	if(isset($_REQUEST["ftp_pass"])){ // OK
		$value = $_REQUEST["ftp_pass"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern9 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern9, $line);
	}
	if(isset($_REQUEST["XXXX"])){
		$value = $_REQUEST["XXXX"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern10 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern10, $line);
	}
	if(isset($_REQUEST["XXXX"])){
		$value = $_REQUEST["XXXX"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern11 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern11, $line);
	}
	if(isset($_REQUEST["XXXX"])){
		$value = $_REQUEST["XXXX"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern12 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern12, $line);
	}
	if(isset($_REQUEST["XXXX"])){
		$value = $_REQUEST["XXXX"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern13 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern13, $line);
	}
	if(isset($_REQUEST["XXXX"])){
		$value = $_REQUEST["XXXX"];
		//echo "Saving $value..." . $brnl;
		$line = "\tpublic " . $pattern14 . " = \"" . $value . "\";";
		searchAndReplace2($file, $pattern14, $line);
	}

	//print_r($_REQUEST);
}


function printLogs(){
	global $config, $brnl;

	$narray = array();

	$id  = "";
	if(isset($_REQUEST["logfile"])){
		$id = $_REQUEST["logfile"];
	}

	//echo "id: " . $id . $brnl;

	$path = $config->hylafax_log_path;

	if (is_readable($path)) {
		echo "Logs path: " . $path . $brnl;
	} else {
		echo "The path $path is not readable" . $brnl;
	}

	if (is_dir($path)) {
		$i = 0;
		echo "<p>Chose the log file to analyze</p>";
		echo "<form name=\"input\" action=\"#\" method=\"post\">";
		//echo "<label for=\"field_log\">File di log: </label>";
		echo "<select id=\"field_log\" name=\"logfile\" size=\"5\">";
		$narray = getFilesFromDir($path);
		if(count($narray)>0){
					rsort($narray);
					for($i=0;$i<sizeof($narray);$i++) {
						$filename = $narray[$i];
						if($filename!="seqf"){
							$fullpath = $path . "/" . $filename;
							$desc =  $filename . " (" . date(getDateFormat(), filemtime($fullpath)) . ")";
							echo "<option value=\"" . $filename . "\">" . $desc . "</option>";
						}
					}
		}
		echo "</select>";
		echo "<p><input type=\"submit\" value=\"Visualizza\" /></p>";
		echo "</form>";
	}


	if($id!=""){
		$content = "";
		$fullpath = $path . "/" . $id;
		if(is_readable($fullpath)){
			$f = fopen($fullpath, "r");
			while ( $line = fgets($f, 1000) ) {
				$content = $content . $line;
			}

			echo "<p>Selected log file: $fullpath</p>";
			//echo "<p>" . date(getDateFormat(), filemtime($fullpath)) . "</p>";
			//echo "<p>" . date(getDateFormat(), filectime($fullpath)) . "</p>";

			echo "<div>";
			echo "<textarea name=\"log\" cols=\"100\" rows=\"20\">";
			echo $content;
			echo "</textarea>";
			echo "</div>";
		}else{
			echo "File $filename is not readable" . $brnl;
		}

		//echo "<p><a href=\"?action=chmod\">Modifica autorizzazioni</a></p>";

	}

	$n = countFilesInDir($path);
	echo "<p>total logs file in $path: $n</p>";
	$n2 = sizeof($narray);
	echo "<p>total logs file shown: $n2</p>";
}


function chmodLogFiles(){
	global $config;
	$path = $config->hylafax_log_path . "/*";
	$cmd1 = "chmod 777 " . $path;
	printOut("Eseguo: " . $cmd1, "A");
	$out1 = shell_exec($cmd1);
	printOutShell($cmd1, $out1);
	return $out1;
}


function restore(){
	global $config;
	if(($config->hylafax_fax_backup) || ($config->avantfax_fax_backup)){
		printOut("restoring fax...", "A");
		restore_fax();
	}
	if(($config->hylafax_config_backup) || ($config->avantfax_config_backup)){
		printOut("restoring config...", "A");
		restore_config();
	}

}


function backup(){
	global $config;
	if(($config->hylafax_fax_backup) || ($config->avantfax_fax_backup)){
		printOut("backuping fax...", "A");
		backup_fax();
	}
	if(($config->hylafax_config_backup) || ($config->avantfax_config_backup)){
		printOut("backuping config...", "A");
		backup_config();
	}
}


function restore_config(){
	global $app, $config;
	$continue = check1($config->fs_remote_file_config, $config->ftp_file_config);
	if($continue){
		$out1 = get_files($app->fs_local_file_config, $config->fs_remote_file_config, $config->ftp_file_config);
		if($out1!=""){
			printOut($out1, "R");
		}
		$b1 = file_exists($app->fs_local_file_config);
		if($b1){
			$out_array = decompress_files($app->fs_local_file_config);
			printOutArray($out_array);
			$out3 = delAllTempFiles($app->fs_local_file_config);
			if($out3!=""){
				printOut("Output message: " . $out3, "R");
			}
		}else{
			printOut("files $app->fs_local_file_config doesn't exist", "E");
		}
	}else{
		printOut("restore process could not start", "E");
	}
}

function restore_fax(){
	global $app, $config;
	$continue = check1($config->fs_remote_file_fax, $config->ftp_file_fax);
	if($continue){
		$out1 = get_files($app->fs_local_file_fax, $config->fs_remote_file_fax, $config->ftp_file_fax);
		if($out1!=""){
			printOut($out1, "R");
		}
		$b1 = file_exists($app->fs_local_file_fax);
		if($b1){
			$out_array = decompress_files($app->fs_local_file_fax);
			printOutArray($out_array);
			$b3 = $config->avantfax_db_dump;
			if($b3){
				// restore mysql data...
				restore_db();
			}
			$out3 = delAllTempFiles($app->fs_local_file_fax);
			if($out3!=""){
				printOut($out3, "R");
			}
			if($b3){
				$out5 = delAllTempFiles($app->sql_dump_file);
				if($out5!=""){
					printOut($out5, "R");
				}
			}
		}else{
			printOut("files $app->fs_local_file_fax doesn't exist", "E");
		}
	}else{
		printOut("restore process could not start", "E");
	}
}



function backup_config(){
	global $app, $config;
	$continue = check2($config->fs_remote_file_config);
	if($continue){
		$out_array = compress_files("config");
		printOutArray($out_array);
		$out2 = put_files($app->fs_local_file_config, $config->fs_remote_file_config, $config->ftp_file_config);
		if($out2!=""){
			printOut($out2, "R");
		}
		$out4 = delAllTempFiles($app->fs_local_file_config);
		if($out4!=""){
			printOut($out4, "R");
		}
	}else{
		printOut("backup process could not start", "E");
	}
}

function backup_fax(){
	global $app, $config;
	$continue = check2($config->fs_remote_file_fax);
	if($continue){
		$b2 = $config->avantfax_db_dump;
		if($b2){
			// backup mysql
			backup_db();

			// sleep for 4 seconds
			sleep(4);

			$out_array = compress_files("db");
			printOutArray($out_array);
		}

		$out_array = compress_files("fax");
		printOutArray($out_array);
		$out2 = put_files($app->fs_local_file_fax, $config->fs_remote_file_fax, $config->ftp_file_fax);
		if($out2!=""){
			printOut($out2, "R");
		}
		$out4 = delAllTempFiles($app->fs_local_file_fax);
		if($out4!=""){
			printOut($out4, "R");
		}
		if($b2){
			$out5 = delAllTempFiles($app->sql_dump_file);
			if($out5!=""){
				printOut($out5, "R");
			}
		}
	}else{
		printOut("backup process could not start", "E");
	}
}

function printOutArray($out_array){
	global $brnl;
	foreach($out_array as $out){
		if($out!=""){
			printOut($out, "R");
		}
	}
}

// ##################################################################

function delAllTempFiles($filename){
	$out1 = "";
	if(file_exists($filename)){
		printOut("File to delete: $filename (" .  filesize($filename) . ") bytes", "R");
		//sleep(3);
		$cmd1 = "rm -f " . $filename;
		$out1 = shell_exec($cmd1);
		printOutShell($cmd1, $out1);
	}else{
		$out1 = "File $filename doesn't exist";
	}
	return $out1;
}

function decompress_files($filename) {
	global $config, $app;
	// Decompess archive to /temp folder
	$cmd1 = "cd /tmp";
	$cmd2 = "tar -xzvf " . $filename;
	$out1 = shell_exec($cmd1);
	$out2 = shell_exec($cmd2);
	printOutShell($cmd2, $out2);
	$array = array();
	$array[] = $out1;
	$array[] = $out1;
	$array[] = $out2;
	return $out;
}

function compress_files($option) {
	global $config, $app, $brnl;
	// Compress files to /temp dir....

	printOut("compressing...($option)", "A");

	$array = array();

	if($option=="db"){

		$out1 = compress_file($app->sql_dump_file, $app->fs_local_file_fax_tar);
		$array[] = $out1;

	}else if($option=="config"){


		$n1 = count($config->hylafax_config_files);
		$n3 = count($config->avantfax_config_files);

		printOut("Hylafax config files or dirs to backup: " .$n1, "A");
		printOut("Avantfax config files or dirs to backup: " .$n3, "A");

		foreach($config->hylafax_config_files as $f){
			$out1 = compress_file($f, $app->fs_local_file_config_tar);
			$array[] = $out1;
		}

		foreach($config->avantfax_config_files as $f){
			$out3 = compress_file($f, $app->fs_local_file_config_tar);
			$array[] = $out3;
		}

		if(file_exists($app->fs_local_file_config_tar)){
			//$cmd2 = "gzip -f " . $app->fs_local_file_config . " " . $app->fs_local_file_config_tar;
			$cmd2 = "gzip -f " . $app->fs_local_file_config_tar . " >>" . $app->fs_local_file_config;
			$out6 = shell_exec($cmd2);
			printOutShell($cmd2, $out6);
			$array[] = $out6;
		}

	}else if($option=="fax"){

		$n2 = count($config->hylafax_data_files);
		$n4 = count($config->avantfax_data_files);

		printOut("Hylafax data files or dirs to backup: " .$n2, "R");
		printOut("Avantfax config files or dirs to backup: " .$n4, "R");

		foreach($config->hylafax_data_files as $f){
			$out2 = compress_file($f, $app->fs_local_file_fax_tar);
			$array[] = $out2;
		}

		foreach($config->avantfax_data_files as $f){
			$out4 = compress_file($f, $app->fs_local_file_fax_tar);
			$array[] = $out4;
		}

		if(file_exists($app->fs_local_file_fax_tar)){
			//$cmd1 = "gzip -f " . $app->fs_local_file_fax . " " . $app->fs_local_file_fax_tar;
			$cmd1 = "gzip -f " . $app->fs_local_file_fax_tar . " >>" . $app->fs_local_file_fax;
			$out5 = shell_exec($cmd1);
			printOutShell($cmd1, $out5);
			$array[] = $out5;
		}

	} else {
		printOut("Invalid option " . $option, "E");
	}

	return $array;
}

function compress_file($f, $filename){
	$out1 = "";
	if( (($f!="") && (file_exists($f))) || (strpos($f, "*") !== false) ){
		if( (is_readable($f)) || (strpos($f, "*") !== false) ){
			$params = getTarParamsAdd($filename);
			$cmd1 = "tar " . $params . " " . $filename . " " . $f;
			$out1 = shell_exec($cmd1);
			printOutShell($cmd1, $out1);
		}else{
			printOut("file $f not readable", "E");
		}
	}else{
		printOut("file $f not found", "E");
	}
	return $out1;
}

function getTarParamsAdd($f){
	$param = "-cf";
	if(file_exists($f)){
		$param = "-rf";
	}
	return $param;
}

function get_files($local_file, $remote_file, $server_file){
	global $config, $app;
	$out = "";
	$use_ftp = $config->use_ftp;
	if($use_ftp){
		// get from ftp to /temp
		$ftp_server = $config->ftp_path;
		$ftp_user_name = $config->ftp_user;
		$ftp_user_pass = $config->ftp_pass;
		$conn_id = openFtp($ftp_server, $ftp_user_name, $ftp_user_pass);
		$b = getFtp($conn_id, $local_file, $remote_file);
		if($b){
			$out = "$server_file upload done";
		}else{
			$out = "$server_file upload error";
		}
		closeFtp($conn_id);
	}else{
		// copy from path to /temp
		$cmd = "cp " . $remote_file . " " . $local_file;
		$out = shell_exec($cmd);
	}

	if (file_exists($local_file)){
		// nothing to do
	}

	return $out;
}

function put_files($local_file, $remote_file, $server_file){
	global $config, $app;
	$out = "";
	$use_ftp = $config->use_ftp;
	if (file_exists($local_file)){
		if($use_ftp){
			// put from /temp to ftp
			$ftp_server = $config->ftp_host;
			$ftp_user_name = $config->ftp_user;
			$ftp_user_pass = $config->ftp_pass;
			$conn_id = openFtp($ftp_server, $ftp_user_name, $ftp_user_pass);
			$b = putFtp($conn_id, $local_file, $server_file);
			if($b){
				$out = "$server_file upload done";
			}else{
				$out = "$server_file upload error";
			}
			closeFtp($conn_id);
		}else{
			// copy from /temp to path
			$cmd = "cp " . $local_file . " " . $remote_file;
			$out = shell_exec($cmd);
			printOutShell($cmd, $out);
			$out = "copy done";
		}
	}else{
		$out = "local file $local_file not found";
	}
	return $out;
}

function backup_db(){
	global $config, $app;
	$sql_dump_file = $app->sql_dump_file;
	$cmd = "mysqldump --opt " . $config->db_name . " > " . $sql_dump_file . " -u" . $config->db_user . " -p" . $config->db_pass;
	$out = shell_exec($cmd);
	printOutShell($cmd, $out);
	return $out;
}

function restore_db(){
	global $config, $app;
	$sql_dump_file = $app->sql_dump_file;
	$cmd = "mysql < " . $sql_dump_file . " -u" . $config->db_user . " -p" . $config->db_pass;
	$output = shell_exec($cmd);
	return $output;
}


function check1($fs_remote_file, $ftp_file) {
	global $config;
	if (!($config->use_ftp)) {
		// check if file to restore is present.....
		$b1 = false;
		$b2 = false;
		if (file_exists($fs_remote_file)){
			$b1 = true;
		}else{
			printOut("File $fs_remote_file not found", "E");
		}
		if($b1){
			if (is_readable($fs_remote_file)) {
				$b2 = true;
			}else{
				printOut("File $fs_remote_file not readable", "E");
			}
		}
		$continue = $b1 && $b2;
	} else {
		// check if ftp server is available
		$host = $config->ftp_host;
		$user = $config->ftp_user;
		$pass = $config->ftp_pass;
		$b3 = checkIfFtpReady($host, $user, $pass);
		if($b3){
			$continue = checkIfFileExist($host, $user, $pass, $ftp_file);
			if(!$continue){
				printOut("Error: cant't find the file $ftp_file on $host", "E");
			}
		}else{
			//echo "Error: ftp server $host is not ready" . $brnl;
		}
	}
	return $continue;
}

function check2($filename) {
	global $config;
	if (!($config->use_ftp)) {
		// check if backup path is present.....
		$path = getPathFromFile($filename);
		$b1 = false;
		$b2 = false;
		if (file_exists($path)){
			$b1 = true;
		}else{
			printOut("Path $path not found", "E");
		}
		if($b1){
			if (is_readable($path)) {
				$b2 = true;
			}else{
				printOut("Path $path not readable", "E");
			}
		}
		$continue = $b1 && $b2;
	} else {
		// check if ftp server is available
		$host = $config->ftp_host;
		$user = $config->ftp_user;
		$pass = $config->ftp_pass;
		$continue = checkIfFtpReady($host, $user, $pass);
		if(!$continue){
			printOut("Error: ftp server $host is not ready or account is not valid", "E");
		}
	}
	return $continue;
}

// #################################################################


function printUpdate(){
	global $app, $brnl;
	$version = new version();
	$version->update();
	echo "Home page: " . $version->gui_version . $brnl;
	echo "Installed version: " . $version->gui_version . $brnl;
	echo "Installed version date: " . $version->gui_date . $brnl;
	echo "Last version (available for download): " . $version->gui_last_version . $brnl;
	echo "Last version date: " . $version->gui_last_version_date . $brnl;

	if($version->needUpdate()){
		echo "Need update: download the new version from the <a href=\"" . $app->project_home . "\">project home page</a>" . $brnl;
	}
}

function printMain(){

global $brnl;

	echo "<h2>Info</h2>";

	$now = getNow();
	$hylafax_ver = getHylafaxVersion();
	$avantfax_ver = getAvantfaxVersion();
	$linux_ver = getLinuxVersion();
	$web_server = getWebServerVersion();
	$last_fax_on_db = getLastFaxOnDb();
	$last_fax_on_disk1 = getLastFaxOnDisk1();
	$last_fax_on_disk2 = getLastFaxOnDisk2();
	$last_backup_fax = getLastBackup("fax");
	$last_backup_config = getLastBackup("config");

	echo "Welcome to <b>hylafaxBackup</b>" . $brnl;
	echo "now is: " . $now . $brnl;
	echo "hylafax version: " . $hylafax_ver . $brnl;
	echo "avantfax version: " . $avantfax_ver . $brnl;
	echo "linux version: " . $linux_ver . $brnl;
	echo "web server: " . $web_server . $brnl;
	echo "last received fax on db: " . $last_fax_on_db . $brnl;
	echo "last received fax on disk (hylafax): " . $last_fax_on_disk1 . $brnl;
	echo "last received fax on disk (avantfax): " . $last_fax_on_disk2 . $brnl;
	echo "last faxes backup: " . $last_backup_fax . $brnl;
	echo "last config backup: " . $last_backup_config . $brnl;

} // end main


function bool2checked($b){
	if($b){
		echo "checked=\"checked\"";
	}
}

function printConfig(){

	global $app, $config;
	echo "<h2>Config</h2>";

?>

<form name="myform" action="./index.php?action=saveconfig" method="post">

<h3>Options</h3>
<label for="field11"></label><input id="field11" type="checkbox" name="hylafax_config_backup" <?php bool2checked($config->hylafax_config_backup); ?> />Hylafax config backup<br />
<label for="field12"></label><input id="field12" type="checkbox" name="hylafax_fax_backup" <?php bool2checked($config->hylafax_fax_backup); ?> />Hylafax fax backup<br />
<label for="field13"></label><input id="field13" type="checkbox" name="avantfax_config_backup" <?php bool2checked($config->avantfax_config_backup); ?> />Avantfax config backup<br />
<label for="field14"></label><input id="field14" type="checkbox" name="avantfax_db_dump" <?php bool2checked($config->avantfax_db_dump); ?> />Avantfax data (db) dump<br />
<label for="field15"></label><input id="field15" type="checkbox" name="avantfax_fax_backup" <?php bool2checked($config->avantfax_fax_backup); ?> />Avantfax fax backup<br />

<h3>Db Authentication</h3>
<label for="field30">Db host: </label><input id="field30" type="text" name="db_host" value="<?php echo $config->db_host; ?>" disabled="disabled" /><br />
<label for="field31">Db user: </label><input id="field31" type="text" name="db_user" value="<?php echo $config->db_user; ?>" /><br />
<label for="field32">Db password: </label><input id="field32" type="text" name="db_pass" value="<?php echo $config->db_pass; ?>" /><br />

<h3>Target</h3>
<label for="field21"></label><input id="field21" type="radio" name="group1" value="fs" <?php bool2checked(!$config->use_ftp); ?> />Use file system (external storage, pen drive, samba folder<br />
<label for="field2">Target filename for fax and data: </label><input id="field2" type="text" name="fs_remote_file_fax" value="<?php echo $config->fs_remote_file_fax; ?>" disabled="disabled" /><br />
<label for="field3">Target filename for config: </label><input id="field2" type="text" name="fs_remote_file_config" value="<?php echo $config->fs_remote_file_config; ?>" disabled="disabled" /><br />

<label for="field22"></label><input id="field22" type="radio" name="group1" value="ftp" <?php bool2checked($config->use_ftp); ?> />Use ftp server<br />
<label for="field5">Ftp host: </label><input id="field5" type="text" name="ftp_host" value="<?php echo $config->ftp_host; ?>" /><br />
<label for="field6">Ftp user: </label><input id="field6" type="text" name="ftp_user" value="<?php echo $config->ftp_user; ?>" /><br />
<label for="field7">Ftp password: </label><input id="field7" type="text" name="ftp_pass" value="<?php echo $config->ftp_pass; ?>" /><br />
<label for="field8">Ftp remote file for fax and data: </label><input id="field8" type="text" name="ftp_file_fax" value="<?php echo $config->ftp_file_fax; ?>" disabled="disabled" /><br />
<label for="field9">Ftp remote file for config: </label><input id="field8" type="text" name="ftp_file_config" value="<?php echo $config->ftp_file_config; ?>" disabled="disabled" /><br />
<input type="submit" value="Save" /><br />

</form>

<?php

}

function printMenu(){
?>

<div class="clear" />

<hr />

<h2>Menu</h2>

<ul>
<li><a href="?action=stats">Avantfax statistics</a></li>
<li><a href="?action=operations">Backup/Restore</a></li>
<li><a href="?action=logs">Hylafax log files</a></li>
<li><a href="?action=errorstats">Hylafax error statistics</a></li>
<li><a href="?action=config">Backup config</a></li>
<li><a href="?action=update">Check if a new version is available</a></li>
<li><a href="?action=support">Request tech support</a></li>
<li><a href="?action=main">Back to main page</a></li>
</ul>

<?php
}

function printMenu2(){
?>


<h2>Actions</h2>

<div>
<div class="backup_actions">
<span id="backup_button" class="button"><a href="?action=backup"><img src="../img/backup.jpg" alt="backup" /><br />Start backup now</a></span>
<span id="restore_button" class="button"><a href="?action=restore"><img src="../img/restore.jpg" alt="restore" /><br />Start restore now</a></span>
</div>
</div>

<p> </p>

<?php
}



function getHylafaxVersion(){
	$ver = "unknown";
	$cmd = "faxstat -i";
	$output = shell_exec($cmd);
	$tokens = explode(" ", $output);
	$ver = $tokens[2];
	return $ver;
}

function getAvantfaxVersion(){
	global $config, $brnl;
	$ver = "unknown";
	$file = "";
	$f9 = "/includes/config.php";
	$f0 = $config->avantfax_install_dir . $f9;
	if (file_exists($f0)) {
		//echo "The file $f0 exist";
		$file = $f0;
	}

	if($file!=""){
		$bReadable = false;
		if (is_readable($file)) {
				//echo "File accessibile" . $nl;
				$bReadable = true;
		}else{
				$ver = "File " . $file . " NOT readable";
		}

		if($bReadable){
			$handle = @fopen($file, "r");
			if ($handle) {
				while (!feof($handle)) {
					$buffer = fgets($handle, 4096);
					if(strpos($buffer, "AVANTFAX_VERSION")>0){
						$tokens = explode("=", $buffer);
						$ver = trim($tokens[1]);
						$ver = str_replace("'", "", $ver);
						$ver = str_replace(";", "", $ver);
						//echo "buffer: " . $buffer . $brnl;
						break 1;
					}

				}
				fclose($handle);
			}
		}
	}
	return $ver;
}

function getLastFaxOnDb(){
	global $config;
	$str = "unknown";
	$con = openDb();

	mysql_select_db($config->db_name, $con);
	$query = "SELECT * FROM FaxArchive ORDER BY fid DESC";
	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
	  $id = $row['fid'];
	  $date = $row['lastoperation'];
	  $str = "fax id " . $id . " on " . $date;
	}

	mysql_free_result($result);

	closeDb($con);
	return $str;
}
function getLastFaxOnDisk1(){
	global $config;
	$str = "unknown";
	$path = $config->hylafax_recvq_path;
	$extensions = array("tif", "pdf");
	$filename = getHighestFileTimestamp2($path, $extensions);
	if (file_exists($filename)) {
		$str = $filename . " " . date (getDateFormat(), filemtime($filename));
	}else{
		$str = "no files found";
	}
	return $str;
}
function getLastFaxOnDisk2(){
	global $config;
	$str = "unknown";
	$path = $config->avantfax_recvd_dir;
	$extensions = array("tif", "pdf");
	$filename = getHighestFileTimestamp2($path, $extensions);
	if (file_exists($filename)) {
		$str = $filename . " (" . date (getDateFormat(), filemtime($filename)) . ")";
	}else{
		$str = "no files found";
	}
	return $str;
}

function getLastBackup($option){
	global $config;
	$str = "unknown";

	if($option=="fax"){
		$str = getLastBackup2($config->fs_remote_file_fax, $config->ftp_file_fax);
	}else if ($option=="config"){
		$str = getLastBackup2($config->fs_remote_file_config, $config->ftp_file_config);
	}

	return $str;
}

function getLastBackup2($fs_remote_file, $server_filename){
	global $config;
	$str = "unknown";
	$b = $config->use_ftp;
	if($b==1){
		$conn_id = openFtp($config->ftp_host, $config->ftp_user, $config->ftp_pass);
		$str = $server_filename . " " . getLastFileDateFtp($conn_id, $server_filename);
		closeFtp($conn_id);
	}else{
		if(file_exists($fs_remote_file)){
			$str = $fs_remote_file . " (" . date(getDateFormat(), filemtime($fs_remote_file)) . ")";
		}
	}
	return $str;
}


function printSponsor(){
	global $app;

?>

<b>hylafaxBackup</b> is an open source and free-to-use software. You can download the last source code from <a href="<?php echo $app->project_home; ?>">Google Code hosting site</a><br />
If you need some commercial support on Hylafax or Avantfax feel free to <a href="<?php echo $app->hylafax_support; ?>">contact us</a>.<br />
<br />
Our services include:
<ul>
<li>remote configuration, diagnostic, system recovery or software upgrade</li>
<li>full server installation (hylafax + avantfax + hylafaxbackup) on your exisitng server</li>
<li>custom fax cover design</li>
<li>custom email template design (with custom informations, logo, and layout on all mail that the system send around)</li>
<li>server appliance (hardware) with full configured software (hylafax + avantfax + hylafaxbackupgui)</li>
</ul>

For more info and price of our service please look at our page <a href="<?php echo $app->hylafax_support; ?>">Hylafax services</a>

<?php
}

function printStats(){

	global $config;
	$str = "unknown";
	$con = openDb();

	$data1 = array();
	$data2 = array();
	$data3 = array();
	$data4 = array();

	$year = date("Y"); // current year
	mysql_select_db($config->db_name, $con);
	$query = "SELECT MONTH(archstamp) AS M, YEAR(archstamp) AS Y, count(*) AS T FROM FaxArchive" .
		" GROUP BY MONTH(archstamp), YEAR(archstamp)" .
		" HAVING Y = " . $year .
		" ORDER BY Y, M";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
		$index = $row["M"];
		$value = $row["T"];
		$r = array();
		$r[] = $value;
		$data2["$index"] = $r;
	}

	mysql_free_result($result);

	$month = date("n"); // current month
	$query = "SELECT DAY(archstamp) AS D, MONTH(archstamp) AS M, YEAR(archstamp) AS Y, count(*) AS T FROM FaxArchive" .
		" GROUP BY DAY(archstamp), MONTH(archstamp), YEAR(archstamp)" .
		" HAVING Y = " . $year . " AND M = " . $month .
		" ORDER BY Y, M, D";

	//echo "query : " . $query;

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
		$index = $row["D"];
		$value = $row["T"];
		$r = array();
		$r[] = $value;
		$data1["$index"] = $r;
	}

	mysql_free_result($result);

	$query = "SELECT origfaxnum AS NUM, count(origfaxnum) AS T FROM FaxArchive".
		" GROUP BY origfaxnum" .
		" ORDER BY T DESC LIMIT 0, 20";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
		$index = $row["NUM"];
		$value = $row["T"];
		$r = array();
		$r[] = $value;
		$data3["$index"] = $r;
	}

	mysql_free_result($result);

	closeDb($con);


	$color = null;

	$title = "Fax received / Month " . date("F") . " " . date("Y");
	printTitle($title, "h3");
	$div_name = "fax_month";
	$columns1 = array();
	$columns1["day"] = "string";
	$columns1["fax received"] = "number";
	$data1 = fixArray($data1, 31);
	initColumnsChart($title, $div_name, $div_name, $columns1, $data1, $color, 700, 450);
	printChart($div_name);

	echo "<hr />";

	$title = "Fax received / Year " . date("Y");
	printTitle($title, "h3");
	$div_name = "fax_year";
	$columns2 = array();
	$columns2["months"] = "string";
	$columns2["fax received"] = "number";
	$data2 = fixArray($data2, 12);
	$y = date("Y");
	foreach($data2 as $m=>$r){
		$month_name = getMonthName($m);
		$index = $month_name  . " " . $y;
		$data4["$index"] = $r;
	}
	//print_r($data4);
	initColumnsChart($title, $div_name, $div_name, $columns2, $data4, $color, 700, 450);
	printChart($div_name);

	echo "<hr />";

	$title = "Top 20 senders";
	printTitle($title , "h3");
	$div_name = "top_sender";
	$columns3 = array();
	$columns3["sender"] = "string";
	$columns3["fax sent"] = "number";
	initBarChart($title , $div_name, $div_name, $columns3, $data3, $color, 700, 450);
	printChart($div_name);

}

function printTitle($txt, $tag){
	$title = "<" . $tag . ">" . $txt . "</" . $tag . ">";
	echo $title;
}

function fixArray($data, $n){
	$array = array();
	for ($i = 1; $i <= $n; $i++) {
		 if (!(isset($data["$i"]))){
		 	$r = array();
		 	$r[] = 0;
		 	$array["$i"] = $r;
		 }else{
		 	$r = $data["$i"];
		 	$array["$i"] = $r;
		 }
	}
	return $array;
}

// ############################### FILE SYSTEMS

function getAllFiles($directory, $recursive = true) {
     $result = array();
     $handle =  opendir($directory);
     while ($datei = readdir($handle)) {
          if (($datei != '.') && ($datei != '..')) {
               $file = $directory . "/" . $datei;
               if (is_dir($file)) {
                    if ($recursive) {
                         $result = array_merge($result, getAllFiles($file));
                    }
               } else {
                    $result[] = $file;
               }
          }
     }
     closedir($handle);
     return $result;
}

function getHighestFileTimestamp2($directory, $extensions, $recursive = true) {
     $allFiles = getAllFiles($directory, $recursive);
     $highestKnownTime = 0;
     $highestKnownFile = "";
     foreach ($allFiles as $val) {
     	  $ext = getExtension($val);
     	  if(in_array($ext, $extensions)){
			  $currentValue = filemtime($val);
			  if ($currentValue > $highestKnownTime){
				$highestKnownTime = $currentValue;
				$highestKnownFile = $val;
			  }
          }
     }
     return $highestKnownFile;
}

function getHighestFileTimestamp($directory, $recursive = true) {
     $allFiles = getAllFiles($directory, $recursive);
     $highestKnownTime = 0;
     $highestKnownFile = "";
     foreach ($allFiles as $val) {
          $currentValue = filemtime($val);
          if ($currentValue > $highestKnownTime){
          	$highestKnownTime = $currentValue;
          	$highestKnownFile = $val;
          }
     }
     return $highestKnownFile;
}



// ############################### DATABASE

function closeDb($con){
	mysql_close($con);
}
function openDb(){
	global $config;
	$con = mysql_connect($config->db_host, $config->db_user, $config->db_pass);
	if (!$con) {
		die('Could not connect: ' . mysql_error());
	}
	return $con;
}

// ###############################

function printErrorStats(){

global $brnl, $errors;

$array = array();
$dir = "/etc/hylafax/log";

$narray = getFilesFromDir($dir);



$MONTH_RANGE = 12;
$months = getLastMonths($MONTH_RANGE);


$array_tot_errors = array();			// #1 utilizzato solo per debug
$r_tot_errors = initArray($months);		// #2 year->month->key->key_count
$log_errors_date = array();				// #3 date->filename->keys_array
$log_errors_tot = array();				// #4 filename->keys_array

if(count($narray)>0){
		for($k=0;$k<sizeof($narray);$k++) {

			$fullpath = $dir . "/" . $narray[$k];

			$array_error_key = array();
			$log_errors_temp = array();

			if(!is_readable($fullpath)) { // if file doesn't exist...

				if($fullpath != "/etc/hylafax/log/seqf"){
					echo "The file $fullpath is not readable" . $brnl; // ...stop executing code.
				}

			} else { // if file exists...
				$f = file($fullpath); // ...make new variable...
				if($f){

					for($i = 0; $i < count($f); $i++) { // ...run through the loop...
						foreach($errors as $key=>$value){
							$pos = strpos($f[$i], $value);
							if ($pos !== false) {
								$array_error_key[] = $key;
								$array_tot_errors[] = $key;

								$m_date = getMonthNumFromFile($fullpath);
								$y_date = getYearFromFile($fullpath);
								foreach($months as $y=>$m_array){
									foreach($m_array as $m){
										//echo "<p>Confronto $m_date con $m e $y_date con $y</p>";
										if( ($m_date == $m ) && ($y_date == $y) ){

											// read
											$r_months = $r_tot_errors["$y"];
											$r_keys = $r_months["$m"];
											$n = 0;
											if(isset($r_keys["$key"])){
												$n = $r_keys["$key"];
											}

											// write
											$r_keys["$key"] = $n + 1;
											$r_months["$m"] = $r_keys;
											$r_tot_errors["$y"] = $r_months;
										}
									}
								}
							} // end if
						} // end foreach
					} // end for
				} // end if
			} // end else

			$date = date(getDateFormat(), filemtime($fullpath));

			$log_errors_temp["$fullpath"] = $array_error_key;

			$log_errors_date["$date"] = $log_errors_temp;
			$log_errors_tot["$fullpath"] = $array_error_key;

		} // end for
}

//print_r($log_errors_date);
//echo "log_errors_date size: " . count($log_errors_date);
//echo "array_tot_errors size: " . count($array_tot_errors);

//print_r($r_tot_errors);

$array = array();
foreach($r_tot_errors as $y=>$errors_month){
	foreach($errors_month as $m=>$keys_array){
		if(sizeof($keys_array>0)){
			foreach($keys_array as $key=>$key_count){
				$month_name = getMonthName($m);
				$year_name = substr($y, 2, 2);
				//$year_name = $y;
				$desc = getErrorDesc($key);
				$index = $month_name . " " . $year_name . " - error " . $key . " (" . $desc . ")";
				$array2 = array();
				$array2[] = $key_count;
				$array["$index"] = $array2;

			}
		}
	}
}

//print_r($array);

$columns = array();
$columns["Date"] = "string";
$columns["Error"] = "number";

$div_name = "errors_log1";
$title = "Errors (last $MONTH_RANGE months)";
initBarChart($title, $div_name, $div_name, $columns, $array, null, 600, 600);
printChart($div_name);

echo "<hr />";



$array = array();
// ARRAY INITIALIZING....
foreach($months as $y=>$m_array){
	foreach($m_array as $m){
		$month_name = getFullMonthName($m);
		$year_name = substr($y, 2, 2);
		//$year_name = $y;
		$index = $month_name . " " . $year_name;
		$array2 = array();
		$array2[] = 0;
		$array["$index"] = $array2;
	}
}
foreach($r_tot_errors as $y=>$errors_month){
	foreach($errors_month as $m=>$keys_array){
		$tot = 0;
		foreach($keys_array as $key=>$key_count){

			$month_name = getFullMonthName($m);
			$year_name = substr($y, 2, 2);
			//$year_name = $y;
			$index = $month_name . " " . $year_name;
			// read
			$n=0;
			if(isset($array["$index"])){
				$r_n = $array["$index"];
				$n = $r_n[0];
			}
			// write
			if($n){
				$tot = $key_count + $n;
			}else{
				$tot = $key_count;
			}
			$array2 = array();
			$array2[] = $tot;
			$array["$index"] = $array2;

		}
	}
}



$columns = array();
$columns["Date"] = "string";
$columns["Error"] = "number";

$div_name = "errors_log3";
$title = "Errors (last $MONTH_RANGE months)";
initAreaChart($title, $div_name, $div_name, $columns, $array, null, 600, 300);
printChart($div_name);

echo "<hr />";



$array3 = array();
foreach($r_tot_errors as $y=>$errors_month){
	foreach($errors_month as $m=>$keys_array){
		foreach($keys_array as $key=>$key_count){

		$month_name = getMonthName($m);
		$year_name = substr($y, 2, 2);
		$desc = getErrorDesc($key);
		$index = $key . " (" . $desc . ")";
		$array3["$index"] = $key_count;

		}
	}
}

$columns = array();
$columns["Error"] = "string";
$columns["Number"] = "number";

$div_name = "errors_log2";
$title = "Error codes (last $MONTH_RANGE months)";
initPieChart($title, $div_name, $div_name, $columns, $array3, null, 700, 300);
printChart($div_name);

echo "<hr />";


//echo "<h2>log_errors</h2>";
//print_r($log_errors);
//echo "Example: log_errors Array ( [/etc/hylafax/log/c000001704] => Array ( [0] => E102 [1] => E102 )" . "<br />";


$array_num = array();

// SENDER
$pattern1 = "ANSWER:";
$pattern2 = "REMOTE TSI";
$pattern3 = "SEND FAX:";
$pattern4 = " DEST ";
$pattern5 = " COMMID";

foreach($log_errors_tot as $fullpath=>$array_error_key){


	$answer = false;
	$send = false;

	if(!is_readable($fullpath)) { // if file doesn't exist...

		if($fullpath != "/etc/hylafax/log/seqf"){
			echo "The file $fullpath is not readable" . $brnl; // ...stop executing code.
		}

	} else {
		$log_time = filemtime($fullpath);
		$time_ago = date("M",mktime(0, 0, 0, date("n") - $MONTH_RANGE, date("j"), date("Y")));
		//echo "$filename was last modified: " . date("F d Y H:i:s.", $time);
		if($log_time>=$time_ago){

			$lines = file($fullpath); // ...make new variable...
			if($lines){
				// Loop through our array, show HTML source as HTML source; and line numbers too.
				foreach ($lines as $line_num => $line) {
					// echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";



					$pos = strpos($line, $pattern1);
					if ($pos !== false) {
						$answer = true;
					}else{
						$pos3 = strpos($line, $pattern3);
						if ($pos3 !== false) {
							$send = true;
						}
					}

					if ($answer){
						$pos2 = strpos($line, $pattern2);
						if($pos2 !== false) {
							$start = $pos2 + strlen($pattern2);
							$end = strlen($line);
							$length = $end - $start;
							$num = trim(substr($line, $start, $length));
							// rimuovo le virgolette
							$num = str_replace("\"", "", $num);
							$array_num["$fullpath"] = $num;
							break 1;
						}
					}


					if($send){
						$pos4 = strpos($line, $pattern4);
						if ($pos4 !== false) {
							$start = $pos4 + strlen($pattern4);
							$end = strpos($line, $pattern5);
							if($end === false) {
								$end = strlen($line);
							}
							$length = $end - $start;
							$num = trim(substr($line, $start, $length));
							$array_num["$fullpath"] = $num;
							break 1;
						}
					}

					if($answer){
						$array_num["$fullpath"] = "unknown sender";
					}else if($send){
						$array_num["$fullpath"] = "unknown destination";
					}

				} // end for
			} // end if
		} // end if
	} // end else
} // end for


$array_num2 = array();
foreach ($log_errors_tot as $fullpath=>$array_error_key){

	$num = 0;
	if(isset($array_num["$fullpath"])){
		$num = $array_num["$fullpath"];
	}
	$r_errors = array();

	foreach ($array_error_key as $key){
		$n = 0;
		if(isset($r_errors["$key"])){
			$n = $r_errors["$key"];
		}
		$n = $n + 1;
		$r_errors["$key"] = $n;

	}

	$m = 0;
	if(isset($array_num2["$num"])){
		$r_errors_old = $array_num2["$num"];
		foreach ($r_errors_old as $key2=>$value2){
			$m = 0;
			if($key2 == $key ){
				$m = $value2;
				$r_errors["$key"] = $n + $m;
			}
		}
	}

	$array_num2["$num"] = $r_errors;
}


$array5 = array();
foreach($array_num2 as $num=>$r_errors){
	foreach($r_errors as $key=>$tot){

		$desc = getErrorDesc($key);
		$index = $num . " (error " . $key . " " . $desc . ")";
		$dummy_r = array();
		$dummy_r[] = $tot;
		$array5["$index"] = $dummy_r;
		arsort($array5);
	}
}


$columns = array();
$columns["Number"] = "string";
$columns["Error"] = "number";

$div_name = "errors_log5";
$title = "Error codes for phone number (last $MONTH_RANGE months)";
initBarChart($title, $div_name, $div_name, $columns, $array5, null, 800, 500);
printChart($div_name);




echo "<hr />";
$n = countFilesInDir($dir);
echo "<div>";
echo "Total logs file in $dir: " . $n . "<br />";
echo "Fax numbers detected in log files: " . count($array_num) . "<br />";
//print_r($array_num);
echo "Fax numbers which cause some errors: " . count($array_num2) . "<br />";
//print_r($array_num2);
echo "</div>";

}



function initArray($months){
	$r_output = array();
	foreach($months as $y=>$m_array){
		foreach($m_array as $m){
			$r_zero = array();
			$r_keys = $r_zero;
			$r_months["$m"] = $r_keys;
			$r_output["$y"] = $r_months;
		}
	}
	return $r_output;
}



?>