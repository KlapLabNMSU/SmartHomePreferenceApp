<html>
<body>

<?php

	$val = $_POST['val'];


	$myFile = './SHDS/items/'.$val.'.json';
	$arr_data = array(); //create empty array
	$directory = './SHDS/items/';
	try
	{	//Get ON and OFF data
		//Get Off
		$on1 = $_POST['timeOn1'];
		$on2 = $_POST['timeOn2'];
		$off1 = $_POST['timeOff1'];
		$off2 = $_POST['timeOff2'];
		$on = array($on1,$on2);
		$off = array($off1,$off2);
		//Get form data
		$formdata = Array (
			"0" => Array (
				"name" => $val,
				"ON" => $on,
				"OFF" => $off
			)
		);

		//encode array to json
		$jsondata = json_encode(array('items' => $formdata),JSON_PRETTY_PRINT);
		//write json data into data.json file
		if(file_put_contents($myFile, $jsondata)) {
			echo 'Data successfully saved<br>';
			$link = '<a href="homepage.php">Return to homepage.</a>';
			echo $link;
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
