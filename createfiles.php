<!--
Author: Miguel Fernandez
Contributors: ---
Date Last Modified: 11/24/2021
Description: TODO
Includes: ---
Included In: home.php activedevices.php bindings.php installbinding.php items.php mywebpage2.php registration.php scan.php schedule.php settings.php
Links To: ---
Links From: ---
-->
<html>
<body>
<?php
	chdir('/xampp/htdocs/SmartHomePreferenceApp/');
	$directory = './SHDS/items/';
	$scanned_directory = array_diff(scandir($directory),array('..','.'));
	$output = "";
	exec("java testfile", $output);
	$arr_data = $output;/*
	//This foreach loop looks through all the devices we have and sees if the names match. If they do not belong, they are deleted.
	foreach($scanned_directory as $values) {
		$smol = substr($values, 0, -5);
		$maybe = in_array($smol, $output);
		if(!$maybe){
			//Item that should not be there is found
			$stringy = "rm -rf ./SHDS/items/";
			$stringy .= $values;
			exec($stringy);
		}
		else{
			//Item is found, do nothing
			array_push($arr_data, $smol);
		}
	}
	//This function makes files for all the devices without one.
	foreach($output as $values) {
		$maybe = in_array($values, $arr_data);
		if(!$maybe){
			//Make file if it does not already exist
			include 'testingjson.php';
		}
	}*/
?>
</body>
</html>