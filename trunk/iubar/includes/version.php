<?php

class version {

	public $gui_version = "?";
	public $gui_date = "?";
	public $gui_last_version = "?";
	public $gui_last_version_date = "?";

	function update(){

		$app = new app();

		/*
		$f = $app->version_file;
		if (file_exists($f) && is_readable($f)) {
			$fh = fopen($f, 'r');
			// $contents = fread($fh, filesize($f));
			$array = file($f);
			fclose($fh);

			$this->gui_version = trim($array[1]);
			$this->gui_date = trim($array[2]);

		}else{
			echo "Error reading file: " . $f . "<br />\n";
		}
		*/

		$this->gui_version = $app->version;
		$this->gui_date = $app->version_date;

		$url_update = $app->url_update;
		if (($url_update!=null)){ // file_exists(..) e is_readable(...) non funzionano
			$lines = file($url_update);

			// Loop through our array, show HTML source as HTML source; and line numbers too.
			// foreach ($lines as $line_num => $line) {
			//    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
			// }

			$this->gui_last_version = trim($lines[1]);
			$this->gui_last_version_date = trim($lines[2]);
		}else{
			echo "Url error: " . $url_update . "<br />\n";
		}
	}


	public static function checkVersion($v1, $v2){
		// formato xx.xx.xx
		$r = 0;
		$right1 = ($v1[7] / 10000) +  ($v1[6] / 1000) + ($v1[4] / 100) +  ($v1[3] / 10);
		$right2 = ($v2[7] / 10000) +  ($v2[6] / 1000) + ($v2[4] / 100) +  ($v2[3] / 10);
		$left1 = ($v1[0] * 10) + $v1[1];
		$left2 = ($v2[0] * 10) + $v2[1];

		$ver1 = $left1 + $right1;
		$ver2 = $left2 + $right2;

		if(($ver1=="") && ($ver2>0)){
			$r = 1;
		}

		if( ($ver1<$ver2) && ($ver1>0) && ($ver2>0) ) {
			$r = 1;
		}
		return $r;
	}

	function needUpdate(){
		$b = false;
		//echo "gui_version: " . $this->gui_version; // test
		$b = version::checkVersion($this->gui_version, $this->gui_last_version);
		return $b;
	}



}

?>