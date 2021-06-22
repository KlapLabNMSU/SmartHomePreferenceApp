<html>
<body>

<?php

	$myFile = "data.json";
	$arr_data = array(); //create empty array

	try
	{
		//Get form data
		$formdata = array{
			'deviceName'=> $_POST['deviceName'],
			'timeOn1'=> $_POST['timeOn1'],
			'timeOn2'=> $_POST['timeOn2'],
			'timeOff1'=> $_POST['timeOff1'],
			'timeOff2'=> $_POST['timeOff2']
		};

		//Get data from existing json file
		$jsondata = file_get_contents($myFile);

		//converts json data into array
		$arr_data = json_decode($jsondata, true);

		//Push user data to array
		array_push($arr_data, $formdata);

		//Convert updated array to JSON
		$jsondata = json_encode($arr_data,JSON_PRETTY_PRINT);

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
