
<?php


// ############################### FTP FUNCTION

function checkFtpLogin($host, $user, $pass){
	$b = false;
	// Connect to ...
	$conn_id = ftp_connect($host);

	// Open a session to an external ftp site
	$login_result = ftp_login ($conn_id, $user, $pass);

	// Check open
	if ((!$conn_id) || (!$login_result)) {
        //echo "Ftp-connect failed!"; die;
        $b = false;
    } else {
        //echo "Connected.";
        $b = true;
    }
    return $b;
}

function checkSocket($host, $port){
	$b = false;
	$socket = socket_create(AF_INET, SOCK_STREAM, 0);
	$sk = socket_connect($socket, $host, $port);
	socket_set_nonblock($socket);
	if (!$sk) {
		$b = false;
		//echo "Not connected Yet \n";
	} else {
		$b = true;
		//echo "Not connected Yet \n";
	}
	return $b;
}

function openFtp($ftp_server, $ftp_user_name, $ftp_user_pass){
	global $config;
	// set up basic connection
	$conn_id = ftp_connect($ftp_server);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	if ((!$conn_id) || (!$login_result)) {
		//die("FTP connection has failed !");
	}

	// print the current directory
	//	echo ftp_pwd($conn_id);

	return $conn_id;
}

function putFtp($conn_id, $local_file, $remote_file){
	// upload a file
	$b = false;
	if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
		//echo "successfully uploaded $file\n";
		$b = true;
	} else {
		//echo "There was a problem while uploading $file\n";
		$b = false;
	}
	return $b;
}

function getFtp($conn_id, $local_file, $server_file){
	// try to download $server_file and save to $local_file
	$b = false;
	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		//echo "Successfully written to $local_file\n";
		$b = true;
	} else {
		//echo "There was a problem\n";
		$b = false;
	}
	return $b;
}



function closeFtp($conn_id){
	// close this connection
	$result = ftp_close($conn_id);
	return $result;
}

function setDirFtp($conn_id, $dir) {
	$result = false;
	// try to change the directory to somedir
	if (ftp_chdir($conn_id, $dir)) {
		//echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
		$result = true;
	} else {
		//echo "Couldn't change directory\n";
		$result = false;
	}
	return $result;
}

function getLastFileDateFtp($conn_id, $file){
	$date = "unknown";
	//  get the last modified time
	$buff = ftp_mdtm($conn_id, $file);

	if ($buff!=-1) {
		// somefile.txt was last modified on: March 26 2003 14:16:41.
		//echo "$file was last modified on : " . date("F d Y H:i:s.", $buff);
		$date = date(getDateFormat(), $buff);
	} else {
		echo "Couldn't get mdtime";
	}
	return $date;
}

function checkIfFtpReady($ftp_server, $ftp_user_name, $ftp_user_pass) {
	global $brnl;
	$b = false;
	$port = 21;
	$b2 = checkSocket($ftp_server, $port);
	if($b2){
		$b3 = checkFtpLogin($ftp_server, $ftp_user_name, $ftp_user_pass);
		if($b3){
			$b = true;
		}else{
			echo "Error: login fail on ftp server $host with user $user" . $brnl;
		}
	}else{
		echo "Error: ftp server $host is not ready" . $brnl;
	}
	return $b;
}

function checkIfFileExist($ftp_server, $ftp_user_name, $ftp_user_pass, $ftp_file){
	$b = false;

	// set up basic connection
	$conn_id = ftp_connect($ftp_server);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	// get the size of $file
	$res = ftp_size($conn_id, $ftp_file);

	if ($res != -1) {
		//echo "size of $file is $res bytes";
		$b = true;
	} else {
		//echo "couldn't get the size";
		$b = false;
	}
	// close the connection
	ftp_close($conn_id);

	return $b;
}

?>