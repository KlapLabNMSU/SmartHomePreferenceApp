<html>
<body>

<?php

	$myFile = './SHDS/items/'."item3.json";
	$arr_data = array(); //create empty array
	$directory = './SHDS/items/';
	//This is for future "reading file name" stuffs :)
	$scanned_directory = array_diff(scandir($directory),array('..','.'));
	foreach($scanned_directory as $values) {
		print_r(substr($values, 0, -5));
		print "<br>";
	}

	//This is to read file stuffs
	$homepage = file_get_contents($directory.'item1.json');
	print_r($homepage);
	print "<br>";

	try
	{	//Get ON and OFF data
		//Get Off ;D
		$on1 = $_POST['timeOn1'];
		$on2 = $_POST['timeOn2'];
		$off1 = $_POST['timeOff1'];
		$off2 = $_POST['timeOff2'];
		$on = array($on1,$on2);
		$off = array($off1,$off2);
		//Get form data
		$formdata = Array (
			"0" => Array (
				"name" => $_POST['deviceName'],
				"ON" => $on,
				"OFF" => $off
			)
		);

		//encode array to json
		$jsondata = json_encode(array('items' => $formdata),JSON_PRETTY_PRINT);
		//write json data into data.json file
		if(file_put_contents($myFile, $jsondata)) {
			echo 'Data successfully saved';
		}
		else
			echo "error";

	}
	//end of try

	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(),"\n";
	}
?>

</body>
</html>
