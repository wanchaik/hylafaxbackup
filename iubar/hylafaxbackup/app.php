<?php

class app {

	// OTHER CONFIG ( don't change anything from here)

	public $project_name = "hylafaxBackup";
	public $project_home = "http://code.google.com/p/hylafaxbackup/";
	public $hylafax_support = "http://www.iubar.it/wiki/index.php?title=Hylafax_and_Avantfax_services";
	public $version = "00.02.00";
	public $version_date = "31/09/2009";
	public $url_update = "http://www.iubar.it/updates/hylafaxbackup/version.txt";

	public $fs_local_file_fax_tar = "/tmp/hylafaxbackup_fax_and_data_tmp.tar";
	public $fs_local_file_config_tar = "/tmp/hylafaxbackup_config_tmp.tar";
	public $fs_local_file_fax = "/tmp/hylafaxbackup_fax_and_data_tmp.tar.gz";
	public $fs_local_file_config = "/tmp/hylafaxbackup_config_tmp.tar.gz";

	public $sql_dump_file = "/tmp/avantfax_dump_tmp.sql";

}

?>