<?php

class config {

	// GLOBAL CONFIG

	public $debug = 1;
	public $hylafax_fax_backup = 1;
	public $hylafax_config_backup = 0;

	public $avantfax_config_backup = 0;
	public $avantfax_fax_backup = 1;
	public $avantfax_db_dump = 1;

	public $use_ftp = 0;

	// FTP CONFIG

	public $ftp_host = "127.0.0.1";
	public $ftp_user = "anonymous";
	public $ftp_pass = "";
	public $ftp_file_fax = "hylafaxbackup_fax_and_data.tar.gz";
	public $ftp_file_config = "hylafaxbackup_config.tar.gz";
	public $ftp_dir = "";

	// FILE SYSTEM CONFIG

	public $fs_remote_file_fax = "/tmp/hylafaxbackup_fax_and_data.tar.gz";
	public $fs_remote_file_config = "/tmp/hylafaxbackup_config.tar.gz";

	// DB CONFIG

	public $db_name = "avantfax";
	public $db_user = "avantfax";
	public $db_pass = "d58fe49";
	public $db_host = "127.0.0.1";


	// HYLAFAX CONFIG

	public $hylafax_recvq_path = "/var/spool/hylafax/recvq";
	public $hylafax_sendq_path = "/var/spool/hylafax/sendq";
	//public $hylafax_log_path = "/etc/hylafax/log";
	public $hylafax_log_path = "/var/spool/hylafax/log";

	public $hylafax_config_files = array();
	public $hylafax_data_files = array();



	// AVANTFAX CONFIG

	public $avantfax_install_dir = "/var/www/html/avantfax";
	public $avantfax_recvd_dir = "";
	public $avantfax_sent_dir = "";
	public $avantfax_config_files = array();
	public $avantfax_data_files = array();


	public function __construct() {

		// HYLAFAX
		if($this->hylafax_config_backup==1){
			$this->hylafax_config_files[] = "/etc/hylafax/faxcover.ps";
			$this->hylafax_config_files[] = "/etc/hylafax/hfaxd.conf";
			$this->hylafax_config_files[] = "/etc/hylafax/hyla.conf";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/config";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/config.ttyS0";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/hosts.hfaxd";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/FaxDispatch";

			$this->hylafax_config_files[] = "/etc/hylafax/etc/cover.templ";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/dialrules";
			$this->hylafax_config_files[] = "/etc/hylafax/etc/templates/custom"; // dir
			$this->hylafax_config_files[] = "/var/spool/hylafax/bin/notify";
			$this->hylafax_config_files[] = "/var/spool/hylafax/bin/faxrcvd";

			$this->hylafax_config_files[] = "/var/www/html/iubar/hylafaxbackup/config.php";

		}
		if($this->hylafax_fax_backup==1){
			$this->hylafax_data_files[] = $this->hylafax_recvq_path;
			$this->hylafax_data_files[] = $this->hylafax_sendq_path;
		}




		// AVANTFAX
		if($this->avantfax_config_backup==1){
			$this->avantfax_config_files[] = $this->avantfax_install_dir . "/includes/config.php";
			$this->avantfax_config_files[] = $this->avantfax_install_dir . "/includes/faxrcvd.php";
			$this->avantfax_config_files[] = $this->avantfax_install_dir . "/includes/notify.php";
		}
		if($this->avantfax_fax_backup==1){
			$this->avantfax_recvd_dir = $this->avantfax_install_dir . "/faxes/recvd";
			$this->avantfax_sent_dir = $this->avantfax_install_dir . "/faxes/sent";
			$this->avantfax_data_files[] = $this->avantfax_recvd_dir;
			$this->avantfax_data_files[] = $this->avantfax_sent_dir;
		}

	}

}

?>
