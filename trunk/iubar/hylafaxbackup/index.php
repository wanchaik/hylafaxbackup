<?php

include_once("../includes/miscs.php");
include_once("../includes/google.php");
include_once("app.php");
include_once("../includes/version.php");
include_once("../includes/ftp.php");
include_once("../includes/system.php");
include_once("config.php");

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

function saveconfig() {
	printOut("Function not ready in this version, please edit manually the file config.php", "W");
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
		$filename = $path . "/" . $id;
		if(is_readable($filename)){
			$f = fopen($filename, "r");
			while ( $line = fgets($f, 1000) ) {
				$content = $content . $line;
			}

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
<label for="field11"></label><input id="field11" type="checkbox" name="hylafax_config_backup" <?php bool2checked($config->hylafax_config_backup); ?> disabled="disabled" />Hylafax config backup<br />
<label for="field12"></label><input id="field12" type="checkbox" name="hylafax_fax_backup" <?php bool2checked($config->hylafax_fax_backup); ?> disabled="disabled" />Hylafax fax backup<br />
<label for="field13"></label><input id="field13" type="checkbox" name="avantfax_config_backup" <?php bool2checked($config->avantfax_config_backup); ?> disabled="disabled" />Avantfax config backup<br />
<label for="field14"></label><input id="field14" type="checkbox" name="avantfax_db_dump" <?php bool2checked($config->avantfax_db_dump); ?> disabled="disabled" />Avantfax data (db) dump<br />
<label for="field15"></label><input id="field15" type="checkbox" name="avantfax_fax_backup" <?php bool2checked($config->avantfax_fax_backup); ?> disabled="disabled" />Avantfax fax backup<br />

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
	$f1 = "/var/www/html/avantfax" . $f9;
	$f2 = "/var/www/avantfax" . $f9;
	if (file_exists($f0)) {
		//echo "The file $f0 exist";
		$file = $f2;
	} else if (file_exists($f1)) {
		//echo "The file $f1 exists";
		$file = $f1;
	} else if (file_exists($f2)) {
		//echo "The file $f2 exist";
		$file = $f2;
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
						$tokens = explode(" ", $buffer);
						$ver = $tokens[2];
						//echo "buffer: " . $buffer . $brnl;
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

function getExtension($filename){
	$ext = "";
	$tokens = explode(".", $filename);
	$length = count($tokens);
	if($length>0){
		$ext = $tokens[$length-1];
	}
	return $ext;
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


// ############################### DATE

function getNow(){
	//date_default_timezone_set('UTC');
	return date(getDateFormat());
}

function getDateFormat(){
	$format = DATE_RFC822;
	//$format = "F d Y H:i:s.";
	return $format;
}


// ############################### DEBUG


function printDebug($txt){
	global $config, $brnl;
	if($config->debug){
		echo "<span class=\"debug_text\">" . $txt . "</span>" . $brnl;
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

function printOutShell($cmd, $out){
	if($out==""){
		$out = "OK";
	}
	printOut("shell_exec " . $cmd . " --> " . $out, "A");
}

function printErrorStats(){

global $brnl;

$errors = array();
$errors["E000-E049"] = "call failures";

$errors["E000"] = "Call successful";

$errors["E001"] = "Busy signal detected";
$errors["E002"] = "No carrier detected";
$errors["E003"] = "No answer from remote";
$errors["E004"] = "No local dialtone";
$errors["E005"] = "Invalid dialing command";
$errors["E006"] = "Unknown problem";
$errors["E007"] = "Carrier established, but Phase A failure";
$errors["E008"] = "Data connection established (wanted fax)";
$errors["E009"] = "Glare - RING detected";
$errors["E010"] = "Blacklisted by modem";
$errors["E011"] = "Ringback detected, no answer without CED";
$errors["E012"] = "Ring detected without successful handshake";

$errors["E050-E099"] = "non Class-specific fax protocol failures";

$errors["E050"] = "Missing EOL after 5 seconds";
$errors["E051"] = "Procedure interrupt received, job terminated";
$errors["E052"] = "Write error to TIFF file";

$errors["E100-E199"] = "Class 1-specific protocol failure";

$errors["E100"] = "Failure to receive silence (synchronization failure).";
$errors["E101"] = "Failure to raise V.21 transmission carrier.";
$errors["E102"] = "No sender protocol (T.30 T1 timeout)";
$errors["E103"] = "RSPREC error/got DCN (sender abort)";
$errors["E104"] = "RSPREC invalid response received";
$errors["E105"] = "Failure to train modems";
$errors["E106"] = "RSPREC error/got EOT";
$errors["E107"] = "Can not continue after DIS/DTC";
$errors["E108"] = "COMREC received DCN (sender abort)";
$errors["E109"] = "No response to RNR repeated 3 times.";
$errors["E110"] = "COMREC invalid response received";
$errors["E111"] = "V.21 signal reception timeout; expected page possibly not received in full";
$errors["E112"] = "Failed to properly detect high-speed data carrier.";
$errors["E113"] = "Received invalid CTC signal in V.34-Fax.";
$errors["E114"] = "Failed to properly open V.34 primary channel.";
$errors["E115"] = "Received premature V.34 termination.";
$errors["E116"] = "Failed to properly open V.34 control channel.";
$errors["E117"] = "COMREC invalid response to repeated PPR received";
$errors["E118"] = "T.30 T2 timeout, expected signal not received";
$errors["E119"] = "COMREC invalid partial-page signal received";
$errors["E120"] = "Cannot synchronize ECM frame reception.";
$errors["E121"] = "ECM page received containing no image data.";
$errors["E122"] = "Remote has no T.4 receiver capability";
$errors["E123"] = "DTC received when expecting DIS (not supported)";
$errors["E124"] = "COMREC error in transmit Phase B/got DCN";
$errors["E125"] = "COMREC invalid command received/no DIS or DTC";
$errors["E126"] = "No receiver protocol (T.30 T1 timeout)";
$errors["E127"] = "Stop and wait failure (modem on hook)";
$errors["E128"] = "Remote fax disconnected prematurely";
$errors["E129"] = "Procedure interrupt (operator intervention)";
$errors["E130"] = "Unable to transmit page (giving up after RTN)";
$errors["E131"] = "Unable to transmit page (giving up after 3 attempts)";
$errors["E132"] = "Unable to transmit page (NAK at all possible signalling rates)";
$errors["E133"] = "Unable to transmit page (NAK with operator intervention)";
$errors["E134"] = "Fax protocol error (unknown frame received)";
$errors["E135"] = "Fax protocol error (command repeated 3 times)";
$errors["E136"] = "DIS/DTC received 3 times; DCS not recognized";
$errors["E137"] = "Failure to train remote modem at 2400 bps or minimum speed";
$errors["E138"] = "Receiver flow control exceeded timer.";
$errors["E139"] = "No response to RR repeated 3 times.";
$errors["E140"] = "COMREC invalid response received to RR.";
$errors["E141"] = "No response to CTC repeated 3 times.";
$errors["E142"] = "COMREC invalid response received to CTC.";
$errors["E143"] = "Failure to transmit clean ECM image data.";
$errors["E144"] = "No response to EOR repeated 3 times.";
$errors["E145"] = "COMREC invalid response received to EOR.";
$errors["E146"] = "COMREC invalid response received to PPS.";
$errors["E147"] = "No response to PPS repeated 3 times.";
$errors["E148"] = "Unable to establish message carrier";
$errors["E149"] = "Unspecified Transmit Phase C error";
$errors["E150"] = "No response to MPS repeated 3 tries";
$errors["E151"] = "No response to EOP repeated 3 tries";
$errors["E152"] = "No response to EOM repeated 3 tries";
$errors["E153"] = "No response to PPM repeated 3 tries";
$errors["E154"] = "Timeout waiting for Phase C carrier drop.";
$errors["E155"] = "PPM received with no image data.  To continue risks receipt confirmation.";

$errors["E200-E299"] = "Class 2-specific protocol failure";

$errors["E200"] = "Unable to request polling operation (modem may not support polling)";
$errors["E201"] = "Unable to setup polling identifer (modem command failed)";
$errors["E202"] = "Unable to setup selective polling address (modem command failed)";
$errors["E203"] = "Unable to setup polling password (modem command failed)";
$errors["E204"] = "Unable to send password (modem command failed)";
$errors["E205"] = "Unable to send subaddress (modem command failed)";
$errors["E206"] = "Unable to restrict minimum transmit speed to %s (modem command failed)";
$errors["E207"] = "Unable to setup session parameters prior to call (modem command failed)";
$errors["E208"] = "Unable to set session parameters";
$errors["E209"] = "<no description>";
$errors["E210"] = "Unknown hangup code";
$errors["E211"] = "Normal and proper end of connection";
$errors["E212"] = "Ring detect without successful handshake";
$errors["E213"] = "Call aborted,  from +FK or <CAN>";
$errors["E214"] = "No loop current";
$errors["E215"] = "Ringback detected, no answer (timeout)";
$errors["E216"] = "Ringback detected, no answer without CED";
$errors["E217"] = "Unspecified Phase A error";
$errors["E218"] = "No answer (T.30 T1 timeout)";
$errors["E219"] = "Unspecified Transmit Phase B error";
$errors["E220"] = "Remote cannot be polled";
$errors["E221"] = "COMREC error in transmit Phase B/got DCN";
$errors["E222"] = "COMREC invalid command received/no DIS or DTC";
$errors["E223"] = "RSPREC error/got DCN";
$errors["E224"] = "DCS sent 3 times without response";
$errors["E225"] = "DIS/DTC received 3 times; DCS not recognized";
$errors["E226"] = "Failure to train at 2400 bps or +FMINSP value";
$errors["E227"] = "RSPREC invalid response received";
$errors["E228"] = "Unspecified Transmit Phase C error";
$errors["E229"] = "Unspecified Image format error";
$errors["E230"] = "Image conversion error";
$errors["E231"] = "DTE to DCE data underflow";
$errors["E232"] = "Unrecognized Transparent data command";
$errors["E233"] = "Image error, line length wrong";
$errors["E234"] = "Image error, page length wrong";
$errors["E235"] = "Image error, wrong compression code";
$errors["E236"] = "Unspecified Transmit Phase D error, including +FPHCTO timeout between data and +FET command";
$errors["E237"] = "RSPREC error/got DCN";
$errors["E238"] = "No response to MPS repeated 3 times";
$errors["E239"] = "Invalid response to MPS";
$errors["E240"] = "No response to EOP repeated 3 times";
$errors["E241"] = "Invalid response to EOP";
$errors["E242"] = "No response to EOM repeated 3 times";
$errors["E243"] = "Invalid response to EOM";
$errors["E244"] = "Unable to continue after PIN or PIP";
$errors["E245"] = "Unspecified Receive Phase B error";
$errors["E246"] = "RSPREC error/got DCN";
$errors["E247"] = "COMREC error";
$errors["E248"] = "T.30 T2 timeout, expected page not received";
$errors["E249"] = "T.30 T1 timeout after EOM received";
$errors["E250"] = "Unspecified Phase C error, including too much delay between TCF and +FDR command";
$errors["E251"] = "Missing EOL after 5 seconds (section 3.2/T.4)";
$errors["E252"] = "DCE to DTE buffer overflow";
$errors["E253"] = "Bad CRC or frame (ECM or BFT modes)";
$errors["E254"] = "Unspecified Phase D error";
$errors["E255"] = "RSPREC invalid response received";
$errors["E256"] = "COMREC invalid response received";
$errors["E257"] = "Unable to continue after PIN or PIP, no PRI-Q";
$errors["E258"] = "Command or signal 10 sec. timeout";
$errors["E259"] = "Cannot send: +FMINSP > remote's +FDIS(BR) code";
$errors["E260"] = "Cannot send: remote is V.29 only, local DCE constrained to 2400 or 4800 bps";
$errors["E261"] = "Remote station cannot receive (DIS bit 10)";
$errors["E262"] = "+FK aborted or <CAN> aborted";
$errors["E263"] = "+Format conversion error in +FDT=DF,VR, WD,LN Incompatible and inconvertable data format";
$errors["E264"] = "Remote cannot receive";
$errors["E265"] = "After +FDR, DCE waited more than 30 seconds for XON from DTE after XOFF from DTE";
$errors["E266"] = "In Polling Phase B, remote cannot be polled";

$errors["E267-279"] = "(currently unused)";

$errors["E280"] = "Procedure interrupt (operator intervention)";
$errors["E281"] = "Unable to transmit page (giving up after RTN)";
$errors["E282"] = "Unable to transmit page (giving up after 3 attempts)";
$errors["E283"] = "Unable to transmit page (NAK at all possible signalling rates)";
$errors["E284"] = "Unable to transmit page (NAK with operator intervention)";
$errors["E285"] = "Modem protocol error (unknown post-page response)";
$errors["E286"] = "Batching protocol error";
$errors["E287"] = "Communication failure during Phase B/C";
$errors["E288"] = "Communication failure during Phase B/C (modem protocol botch)";

$errors["E300-E399"] = "Non-T.30 client or server failure";

$errors["E301"] = "Receive aborted due to operator intervention";
$errors["E302"] = "Problem reading document directory";
$errors["E303"] = "Internal botch; %s post-page handling string"; // "Internal botch; %s post-page handling string \"%s\"";
$errors["E304"] = "Maximum receive page count exceeded, call terminated";
$errors["E305"] = "Could not fork for scripted configuration.";
$errors["E306"] = "Bad exit status %#o for '%s'";
$errors["E307"] = "Could not open a pipe for scripted configuration.";
$errors["E308"] = "ANSWER: CALL REJECTED";
$errors["E309"] = "ANSWER: Call deduced as %s, but told to answer as %s; call ignored";
$errors["E310"] = "External getty use is not permitted {E310}";
$errors["E311"] = "%s: could not create";
$errors["E312"] = "%s: can not fork: %s";
$errors["E313"] = "ERROR: Unknown status";
$errors["E314"] = "Can not open document file %s";
$errors["E315"] = "Can not set directory %u in document file %s";
$errors["E316"] = "Error reading directory %u in document file %s";
$errors["E317"] = "Too many pages in submission; max %u";
$errors["E318"] = "Unable to lock shared document file";
$errors["E319"] = "Unable to open shared document file";
$errors["E320"] = "Unable to create document file";
$errors["E321"] = "Converted document is not valid TIFF";
$errors["E322"] = "Could not reopen converted document to verify format";
$errors["E323"] = "Job contains no documents";
$errors["E324"] = "Modem does not support polling";
$errors["E325"] = "Kill time expired";
$errors["E326"] = "Invalid or corrupted job description file";
$errors["E327"] = "REJECT: Unable to convert dial string to canonical format";
$errors["E328"] = "REJECT: Requested modem %s is not registered";
$errors["E329"] = "REJECT: No work found in job file";
$errors["E330"] = "REJECT: Page width (%u) appears invalid";
$errors["E331"] = "REJECT: Job expiration time (%u) appears invalid";
$errors["E332"] = "REJECT: Time-to-send (%u) appears invalid";
$errors["E333"] = "REJECT: Too many attempts to dial";
$errors["E334"] = "REJECT: Too many attempts to transmit: %u, max %u";
$errors["E335"] = "REJECT: Too many pages in submission: %u, max %u";
$errors["E336"] = "REJECT: Modem is configured as exempt from accepting jobs";
$errors["E337"] = "Blocked by concurrent calls";
$errors["E338"] = "Delayed by time-of-day restrictions";
$errors["E339"] = "Delayed by outbound call staggering";
$errors["E340"] = "Could not fork to prepare job for transmission";
$errors["E341"] = "Could not fork to start job transmission";
$errors["E342"] = "Delayed by prior call";
$errors["E343"] = "Send program terminated abnormally; unable to exec %s";
$errors["E344"] = "Job interrupted by user";
$errors["E345"] = "Job aborted by request";

$errors["E400-E499"] = "job/modem incompatibility";

$errors["E400"] = "Modem does not support negotiated signalling rate";
$errors["E401"] = "Modem does not support negotiated min scanline time";
$errors["E402"] = "Document is not in a Group 3 or Group 4 compatible format (compression %u)";
$errors["E403"] = "Document was encoded with 2DMMR, but client does not support this data format";
$errors["E404"] = "Document was encoded with 2DMMR, but modem does not support this data format";
$errors["E405"] = "Document was encoded with 2DMMR, but ECM is not being used.";
$errors["E406"] = "Document was encoded with 2DMR, but client does not support this data format";
$errors["E407"] = "Document was encoded with 2DMR, but modem does not support this data format";
$errors["E408"] = "Hyperfine resolution document is not supported by client, image resolution %g x %g lines/mm";
$errors["E409"] = "Hyperfine resolution document is not supported by modem, image resolution %g x %g lines/mm";
$errors["E410"] = "Superfine resolution document is not supported by client, image resolution %g lines/mm";
$errors["E411"] = "Superfine resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E412"] = "300x300 resolution document is not supported by client, image resolution %g lines/mm";
$errors["E413"] = "300x300 resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E414"] = "High resolution document is not supported by client, image resolution %g lines/mm";
$errors["E415"] = "High resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E416"] = "Client does not support document page width, max remote page width %g pixels, image width %lu pixels";
$errors["E417"] = "Modem does not support document page width, max page width %g pixels, image width %lu pixels";
$errors["E418"] = "Client does not support document page length, max remote page length %d mm, image length %lu rows (%.2f mm)";
$errors["E419"] = "Modem does not support document page length, max page length %s mm, image length %lu rows (%.2f mm)";

$errors["E500-E599"] = "paging failures";

$errors["E500"] = "No initial ID response from paging central";
$errors["E501"] = "Login failed multiple times";
$errors["E502"] = "Protocol failure: %s from paging central";
$errors["E503"] = "Protocol failure: %s waiting for go-ahead message";
$errors["E504"] = "Message block not acknowledged by paging central after multiple tries";
$errors["E505"] = "Message block transmit failed paging central rejected it";
$errors["E506"] = "Protocol failure: paging central responded to message block transmit with forced disconnect";
$errors["E507"] = "Protocol failure: %s to message block transmit";
$errors["E508"] = "Paging central rejected content; check PIN";
$errors["E509"] = "Protocol failure: timeout waiting for transaction ACK/NAK from paging central";


$array = array();
$dir = "/etc/hylafax/log";

$narray = getFilesFromDir($dir);

$MONTH_RANGE = 12;
$months = getLastMonths($MONTH_RANGE);



$array_tot_errors = array();
$r_tot_errors = initArray($months);
$log_errors_date = array();

if(count($narray)>0){
		for($k=0;$k<sizeof($narray);$k++) {
			$fullpath = $dir . "/" . $narray[$k];
			$array_file_errors = array();
			if(!is_readable($fullpath)) { // if file doesn't exist...

				if($fullpath != "/etc/hylafax/log/seqf"){
					echo "The file $fullpath is not readable" . $brnl; // ...stop executing code.
				}

			} else { // if file exists...
				$f = file($fullpath); // ...make new variable...
				if($f){
					foreach($errors as $key=>$value){
						for($i = 0; $i < count($f); $i++) { // ...run through the loop...
							$pos = strpos($f[$i], $value);
							if ($pos !== false) {
								$array_file_errors[] = $key;
								$array_tot_errors[] = $key;

								$m_date = getMonthFromFile($fullpath);
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

							}
						} // end for
					}
				}
			}
			$log_errors["$fullpath"] = $array_file_errors;
			$date = date(getDateFormat(), filemtime($fullpath));
			$log_errors_date["$date"] = $log_errors;
		}
}

$array = array();
foreach($r_tot_errors as $y=>$errors_month){
	foreach($errors_month as $m=>$keys_array){
		foreach($keys_array as $key=>$key_count){

		$month_name = getMonthName($m);
		$year_name = substr($y, 2, 2);
		//$year_name = $y;
		$index = $month_name . " " . $year_name . " - error code " . $key;

		$array2 = array();
		$array2[] = $key_count;
		$array["$index"] = $array2;

		}
	}
}


$columns = array();
$columns["Date"] = "string";
$columns["Error"] = "number";

$div_name = "errors_log";
$title = "Errors (last $MONTH_RANGE months)";
initBarChart($title, $div_name, $div_name, $columns, $array, null, 600, 300);
printChart($div_name);

echo "<hr />";



$array = array();
// init
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
		$desc = "<no description>";
		if(isset($errors["$key"])){
			$desc = substr($errors["$key"], 0, 25);
		}
		$index = $key . " (" . $desc . ")";
		$array3["$index"] = $key_count;

		}
	}
}

$columns = array();
$columns["Date"] = "string";
$columns["Error"] = "number";

$div_name = "errors_log2";
$title = "Error codes (last $MONTH_RANGE months)";
initPieChart($title, $div_name, $div_name, $columns, $array3, null, 700, 300);
printChart($div_name);

echo "<hr />";

$n = countFilesInDir($dir);
echo "<p>total logs file in $dir: $n</p>";

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


function getMonthFromFile($fullpath){
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

?>