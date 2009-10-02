<?php

class config {

	// GLOBAL CONFIG

	public $avantfax_db_dump = 1;
	public $hylafax_file_backup = 1;
	public $avantfax_file_backup = 1;
	public $use_ftp = 0;

	// FTP CONFIG

	public $ftp_host = "127.0.0.1";
	public $ftp_user = "anonymous";
	public $ftp_pass = "";
	public $ftp_file = "hylafaxbackup.tgz";
	public $ftp_dir = "";

	// FILE SYSTEM CONFIG

	public $fs_remote_file = "/tmp/hylafaxbackup.tgz";

	// DB CONFIG

	public $db_name = "avantfax";
	public $db_user = "avantfax";
	public $db_pass = "d58fe49";
	public $db_host = "127.0.0.1";


	// HYLAFAX CONFIG

	public $hylafax_recvq_path = "/var/spool/hylafax/recvq";
	public $hylafax_sendq_path = "/var/spool/hylafax/sendq";

	// AVANTFAX CONFIG

	public $avantfax_install_dir = "/var/www/html/avantfax";
	public $avantfax_recvd_dir = "/var/www/html/avantfax/faxes/recvd";
	public $avantfax_sent_dir = "/var/www/html/avantfax/faxes/sent";

}

?>
