<?php


include_once("../includes/functions.php");

ini_set("display_errors", "1");
error_reporting(E_ALL);

$br = "<br/>";
$brnl = $br . "\r\n";
$ver = "00.02";
printHeader("hylafaxBackup");


echo "<h2>Info</h2>";

// show hylafax version
// show avantfax version
// show number of fax in DB
// show number of fax on FS
// last fax is
// last fax date on DB
// last fax date on FS

echo "<h2>Config</h2>";

?>

<form name="myform" action="#?action=saveconfig" method="post">

<h3>Destination</h3>

<div align="center"><br />
<input type="radio" name="group1" value="fs" checked>File system (external storage, pen drive, samba folder<br>
<input type="radio" name="group1" value="ftp">Ftp
</div>

<h3>Options</h3>

<table border="0">
<tr>
<th>File system</th><th>Ftp</th>
</tr>
<tr>
	<td>
		Path <input type="text" name="fs_fullpath" /><br />
		File <input type="text" name="fs_filename" /><br />
	</td>
	<td>
		Ftp host <input type="text" name="ftp_host" /><br />
		Ftp user <input type="text" name="ftp_user" /><br />
		Ftp password <input type="text" name="ftp_pass" /><br />
		Ftp File <input type="text" name="ftp_filename" /><br />
	</td>
</tr>

<input type="submit" value="Submit" name="Salva" />

</form>

<?php


echo "<h2>Backup</h2>";


$ftp = 0;

$ftp_address = "";
$ftp_user = "";
$ftp_pass = "";
$ftp_file = "";

$fs = "";
$fs_fullpath = "";


if($ftp){

}else{

}


// dump mysql
// create text file and save
// compress archives
// copy all
// delete temp

echo "<h2>Restore</h2>";


$ftp = 0;

$ftp_address = "";
$ftp_user = "";
$ftp_pass = "";
$ftp_file = "";

$fs = "";
$fs_fullpath = "";


if($ftp){

}else{

}


// move archive to temp
// uncompress archive
// restore mysql
// restore archives
// delete temp

echo "OK";

printFooter2("hylafaxBackup (ver " . $ver . ")", "http://code.google.com/p/hylafaxbackup/");



function saveconfig() {


}


?>