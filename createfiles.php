<html>
<body>
<?php

	$directory = './SHDS/items/';
	$scanned_directory = array_diff(scandir($directory),array('..','.'));
	include 'openhabTest.php';
	$arr_data = array();
	foreach($scanned_directory as $values) {
		$smol = substr($values, 0, -5);
		$maybe = in_array($smol, $output);
		if(!$maybe){
			//delete stuffs
		}
		else
			$arr_data.push($arr_data, $smol);
	}

	foreach($output as $values) {
		$maybe = in_array($values, $arr_data);
		if(!$maybe)
			//Create file w/ name
	}
?>
</body>
</html>
