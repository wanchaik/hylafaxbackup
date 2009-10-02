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

printHeader("hylafaxBackup");

$action = "default";
if(isset($_REQUEST["action"])){
	$action = $_REQUEST["action"];
}

if($action == "saveconfig"){
	saveconfig();
	echo "Config saved.";
} else if($action == "config"){
	printConfig();
} else if($action == "update"){
	printUpdate();
} else if($action == "stats"){
	printStats();
} else if($action == "operations"){
	printMenu2();
} else if($action == "backup"){
	backup();
	echo "Backup done.";
} else if($action == "restore"){
	restore();
	echo "Restore done.";
} else if($action == "support"){
	printSponsor();
} else if($action == "main"){
	printMain();
} else {
	printMain();
}

printMenu();

printFooter2($app->project_name . " (ver " . $app->version . ")", $app->project_home);


// #################################################################

function saveconfig() {
	echo "TODO: function not ready in this version, please edit the file config.php";
}

function restore(){
	global $config, $brnl;
	$continue = check1();
	if($continue){
		$out = get_files();
		echo "Output message: " . $out . $brnl;
		$b1 = file_exists($app->fs_local_file);
		if($b1){
			decompress_files();
			$b3 = $config->db_dump;
			if($b3){
				// restore mysql data...
				restore_db();
			}
			delAllTempFiles();
		}else{
			echo "Error: getting files" . $brnl;
		}
	}else{
		echo "Error: restore process could not start" . $brnl;
	}
}

function backup(){
	global $config, $brnl;
	$continue = check2();
	if($continue){
		$b2 = $config->db_dump;
		if($b2){
			// backup mysql
			backup_db();
		}
		compress_files();
		$out = put_files();
		echo "Output message: " . $out . $brnl;
		delAllTempFiles();
	}else{
		echo "Error: backup process could not start" . $brnl;
	}
}

// ##################################################################

function delAllTempFiles(){
	global $config, $app;
	$filename = $app->fs_local_file;
	$cmd1 = "rm -f " . $filename;
	$out1 = shell_exec($cmd1);
	return $out1;
}

function decompress_files() {
	global $config, $app;
	$filename = $app->fs_local_file;
	// Decompess archive to /temp folder
	$cmd1 = "cd /";
	$cmd2 = "tar -xzvf " . $filename;
	$out1 = shell_exec($cmd1);
	$out2 = shell_exec($cmd2);
	$array = array();
	$array[] = $out1;
	$array[] = $out1;
	$array[] = $out2;
	return $out;
}

function compress_files() {
	global $config, $app;
	// Compress files to /temp dir....
	$filename = $app->fs_local_file;
	$cmd1 = "tar -czf " . $filename . " " . $config->hylafax_recvq_path;	// hylafax
	$cmd2 = "tar -rzf " . $filename . " " . $config->hylafax_sendq_path;	// hylafax
	$cmd3 = "tar -rzf " . $filename . " " . $config->avantfax_recvd_dir;	// avantfax
	$cmd4 = "tar -rzf " . $filename . " " . $config->avantfax_sent_dir;		// avantfax
	$out1 = shell_exec($cmd1);
	$out2 = shell_exec($cmd2);
	$out3 =	shell_exec($cmd3);
	$out4 = shell_exec($cmd4);
	$array = array();
	$array[] = $out1;
	$array[] = $out2;
	$array[] = $out3;
	$array[] = $out4;
	return $out;
}

function get_files(){
	global $config, $app;
	$out = "";
	$use_ftp = $config->use_ftp;
	$local_file = $app->fs_local_file;
	if($use_ftp){
		// get from ftp to /temp
		$remote_file = $config->ftp_file;
		$ftp_server = $config->ftp_path;
		$ftp_user_name = $config->ftp_user;
		$ftp_user_pass = $config->ftp_pass;
		$conn_id = openFtp($ftp_server, $ftp_user_name, $ftp_user_pass);
		$out = getFtp($conn_id, $local_file, $remote_file);
		closeFtp($conn_id);
	}else{
		// copy from path to /temp
		$remote_file = $config->fs_remote_file;
		$cmd = "cp " . $remote_file . " " . $local_file;
		$out = shell_exec($cmd);
	}

	if (file_exists($local_file)){
		// nothing to do
	}

	return $out;
}

function put_files(){
	global $config, $app;
	$out = "";
	$use_ftp = $config->use_ftp;
	$local_file = $app->fs_local_file;
	if (file_exists($local_file)){
		if($use_ftp){
			// put from /temp to ftp
			$server_file = $config->ftp_file;
			$ftp_server = $config->ftp_path;
			$ftp_user_name = $config->ftp_user;
			$ftp_user_pass = $config->ftp_pass;
			$conn_id = openFtp($ftp_server, $ftp_user_name, $ftp_user_pass);
			$out = putFtp($conn_id, $local_file, $server_file);
			closeFtp($conn_id);
		}else{
			// copy from /temp to path
			$remote_file = $config->fs_remote_file;
			$cmd = "cp " . $local_file . " " . $remote_file;
			$out = shell_exec($cmd);
		}
	}else{
		$out = "local file $local_file not found";
	}
	return $out;
}

function backup_db(){
	global $config, $app;
	$sql_dump_file = $app->sql_dump_file;
	$cmd = "mysqldump --opt " . $config->db_name . " > " . $sql_dump_file . " -u " . $config->db_user . " -p " . $config->db_pass;
	$output = shell_exec($cmd);
	return $output;
}

function restore_db(){
	global $config, $app;
	$sql_dump_file = $app->sql_dump_file;
	$cmd = "mysql < " . $sql_dump_file . " -u " . $config->db_user . " -p " . $config->db_pass;
	$output = shell_exec($cmd);
	return $output;
}


function check1() {
	global $config, $brnl;
	if (!($config->use_ftp)) {
		// check if file to restore is present.....
		$file = $config->fs_remote_file;
		$b1 = false;
		$b2 = false;
		if (file_exists($file)){
			$b1 = true;
		}else{
			echo "File $file not found" . $brnl;
		}
		if($b1){
			if (is_readable($file)) {
				$b2 = true;
			}else{
				echo "File $file not readable" . $brnl;
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
			$ftp_file = $config->ftp_file;
			$continue = checkIfFileExist($host, $user, $pass, $ftp_file);
			if(!$continue){
				echo "Error: cant't find the file $ftp_file on $host" . $brnl;
			}
		}else{
			//echo "Error: ftp server $host is not ready" . $brnl;
		}
	}
	return $continue;
}

function check2() {
	global $config, $brnl;
	if (!($config->use_ftp)) {
		// check if backup path is present.....
		$path = getPathFromFile($config->fs_remote_file);
		$b1 = false;
		$b2 = false;
		if (file_exists($path)){
			$b1 = true;
		}else{
			echo "Path $path not found" . $brnl;
		}
		if($b1){
			if (is_readable($path)) {
				$b2 = true;
			}else{
				echo "Path $path not readable" . $brnl;
			}
		}
		$continue = $b1 && $b2;
	} else {
		// check if ftp server is available
		$host = $config->ftp_host;
		$user = $config->ftp_user;
		$pass = $config->ftp_pass;
		$continue = checkIfFtpReady($host, $user, $pass);
		if(!$b3){
			//echo "Error: ftp server $host is not ready" . $brnl;
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
	$last_backup = getLastBackup();

	echo "Welcome to <b>hylafaxBackup</b>" . $brnl;
	echo "now is: " . $now . $brnl;
	echo "hylafax version: " . $hylafax_ver . $brnl;
	echo "avantfax version: " . $avantfax_ver . $brnl;
	echo "linux version: " . $linux_ver . $brnl;
	echo "web server: " . $web_server . $brnl;
	echo "last received fax on db: " . $last_fax_on_db . $brnl;
	echo "last received fax on disk (hylafax): " . $last_fax_on_disk1 . $brnl;
	echo "last received fax on disk (avantfax): " . $last_fax_on_disk2 . $brnl;
	echo "last backup: " . $last_backup . $brnl;

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

<form name="myform" action="#?action=saveconfig" method="post">

<h3>Options</h3>
<label for="field11"></label><input id="field11" type="checkbox" name="avantfax_db_dump" <?php bool2checked($config->avantfax_db_dump); ?> disabled="disabled" />Database dump<br />
<label for="field12"></label><input id="field12" type="checkbox" name="hylafax_file_backup" <?php bool2checked($config->hylafax_file_backup); ?> disabled="disabled" />Hylafax file (fax) backup<br />
<label for="field13"></label><input id="field13" type="checkbox" name="avantfax_file_backup" <?php bool2checked($config->avantfax_file_backup); ?> disabled="disabled" />Avantfax file (fax) backup<br />

<h3>Db Authentication</h3>
<label for="field30">Db host: </label><input id="field30" type="text" name="db_host" value="<?php echo $config->db_host; ?>" disabled="disabled" /><br />
<label for="field31">Db user: </label><input id="field31" type="text" name="db_user" value="<?php echo $config->db_user; ?>" /><br />
<label for="field32">Db password: </label><input id="field32" type="text" name="db_pass" value="<?php echo $config->db_pass; ?>" /><br />

<h3>Target</h3>
<label for="field21"></label><input id="field21" type="radio" name="group1" value="fs" <?php bool2checked(!$config->use_ftp); ?> />Use file system (external storage, pen drive, samba folder<br />
<label for="field2">Path and file name: </label><input id="field2" type="text" name="fs_remote_file" value="<?php echo $config->fs_remote_file; ?>" disabled="disabled" /><br />
<label for="field22"></label><input id="field22" type="radio" name="group1" value="ftp" <?php bool2checked($config->use_ftp); ?> />Use ftp server<br />
<label for="field5">Ftp host: </label><input id="field5" type="text" name="ftp_host" value="<?php echo $config->ftp_host; ?>" /><br />
<label for="field6">Ftp user: </label><input id="field6" type="text" name="ftp_user" value="<?php echo $config->ftp_user; ?>" /><br />
<label for="field7">Ftp password: </label><input id="field7" type="text" name="ftp_pass" value="<?php echo $config->ftp_pass; ?>" /><br />
<label for="field8">Ftp remote file: </label><input id="field8" type="text" name="ftp_file" value="<?php echo $config->ftp_file; ?>" disabled="disabled" /><br />
<input type="submit" value="Save" /><br />

</form>

<?php

}

function printMenu(){
?>

<hr />

<h2>Menu</h2>

<ul>
<li><a href="?action=stats">Avantfax statistics</a></li>
<li><a href="?action=operations">Backup/Restore</a></li>
<li><a href="?action=config">Configuration</a></li>
<li><a href="?action=update">Check if a new version is available</a></li>
<li><a href="?action=support">Request tech support</a></li>
<li><a href="?action=main">Back to main page</a></li>
</ul>

<?php
}

function printMenu2(){
?>

<hr />

<h2>Actions</h2>

<ul>
<li><a href="?action=backup">Start backup now</a></li>
<li><a href="?action=restore">Start restore now</a></li>
</ul>

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
		$str = "file unknown: " . $filename;
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
		$str = $filename . " " . date (getDateFormat(), filemtime($filename));
	}else{
		$str = "file unknown: " . $filename;
	}
	return $str;
}
function getLastBackup(){
	global $config;
	$str = "unknown";
	$b = $config->use_ftp;
	if($b==1){
		$conn_id = openFtp();
		$filename = $config->ftp_file;
		$str = $filename . " " . getLastFileDateFtp($conn_id, $filename);
		closeFtp();
	}else{
		$filename = $config->fs_remote_file;
		if(file_exists($filename)){
			$str = $filename . " " . filemtime($filename);
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
		$data2[$index] = $value;
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
		$data1[$index] = $value;
	}

	mysql_free_result($result);

	$query = "SELECT origfaxnum AS NUM, count(origfaxnum) AS T FROM FaxArchive".
		" GROUP BY origfaxnum" .
		" ORDER BY T DESC LIMIT 0, 20";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
		$index = $row["NUM"];
		$value = $row["T"];
		$data3[$index] = $value;
	}

	mysql_free_result($result);

	closeDb($con);


	$color = null;
	$w = "700";
	$h = "350";

	$title = "Fax received / Month " . date("F") . " " . date("Y");
	printTitle($title, "h3");
	$div_name = "fax_month";
	$columns1 = array();
	$columns1["day"] = "string";
	$columns1["fax received"] = "number";
	$data1 = fixArray($data1, 31);
	initColumnsChart($title, "fax_month", $div_name, $columns1, $data1, $color, $w, $h);
	printChart($div_name);
	echo "<hr />";

	$title = "Fax received / Year " . date("Y");
	printTitle($title, "h3");
	$div_name = "fax_year";
	$columns2 = array();
	$columns2["months"] = "string";
	$columns2["fax received"] = "number";
	$data2 = fixArray($data2, 12);
	initColumnsChart($title, "fax_year", $div_name, $columns2, $data2, $color, $w, $h);
	printChart($div_name);
	echo "<hr />";

	$title = "Top 20 senders";
	printTitle($title , "h3");
	$div_name = "top_sender";
	$columns3 = array();
	$columns3["sender"] = "string";
	$columns3["fax sent"] = "number";
	initBarChart($title , "top_sender", $div_name, $columns3, $data3, $color, $w, $h);
	printChart($div_name);

}

function printTitle($txt, $tag){
	$title = "<" . $tag . ">" . $txt . "</" . $tag . ">";
	echo $title;
}

function fixArray($data, $n){
	$sorted_data = array();
	for ($i = 1; $i <= $n; $i++) {
		 $index = "" . $i;
		 if (!(isset($data[$index]))){
		 	$sorted_data[$i] = 0;
		 }else{
		 	$sorted_data[$i] = $data[$index];
		 }
	}
	return $sorted_data;
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
	return $format;
}

?>