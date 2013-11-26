<?php

include_once("miscs.php");

$br = "<br/>";
$brnl = $br . "\r\n";

echo "<h2>Test</h2>";

	$today_time = time();

	$month_current = getMonthNum($today_time);
	$year_current = getYear($today_time);
	$array = getLastMonths(12);
	$month_name = getMonthName(2);
	$month_name2 = getFullMonthName(2);
	echo "Current month: $month_current" . $brnl;
	echo "Current year: $year_current" . $brnl;
	echo "month name for 2: $month_name" . $brnl;
	echo "month name for 2: $month_name2" . $brnl;
	echo "array content" . $brnl;
	print_r($array);
	echo "<hr />";

$array = array();
$array["andrea"]  = "rossi";
$array["alessandra"]  = "verdi";
$array2b = array();
$array2b["andrea"]  = "rossi";
$array2c = array();
$array2c["alessandra"]  = "verdi";
$array2 = array();
$array2["utente1"]  = $array2b;
$array2["utente2"]  = $array2c;
/*
echo "array[0] " . $array[0] . $brnl;
echo "array[1] " . $array[1] . $brnl;
echo "array2[0] " . $array2[0] . $brnl;
echo "array2[1] " . $array2[1] . $brnl;
echo "array2[0][0] " . $array2[0][0] . $brnl;
echo "array2[0][1] " . $array2[0][1] . $brnl;
*/
?>

