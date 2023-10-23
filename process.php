<<<<<<< Updated upstream
=======
<!--
Author: Miguel Fernandez
Contributors: Jacob Yoder, Diego Terrazas
Date Last Modified: 04/03/2023
Description: Simple php program to grab user inputed data from mywebpage2.php and insert it into mongodDB
			as well as create a JSON file with the data.
Includes: phptomongodb.php
Included In: ---
Links To: home.php
Links From: mywebpage2.php
-->
>>>>>>> Stashed changes
<html>
<body>

<?php
	require __DIR__ . '/vendor/autoload.php';

	$val = $_POST['val'];


	$myFile = './SHDS/devices/'.$val.'.json';
	$arr_data = array(); //create empty array
	$directory = './SHDS/devices/';
	include 'phptomongodb.php';

	$counter = 1;
	if(isset($_POST['counterForm'])){
		$counter = $_POST['counterForm'];
	}

	try{
		
		$temp = "";

		$active = array();
		$inactive = array();
		$days = array();
		$states = array();
		

		for($i = 1; $i <= $counter; $i++){
			//Get ON and OFF data
			//Get Off


			$temp = "timeOn".$i;
			//$on = $_POST[$temp];
			array_push($active, $_POST[$temp]);

			$temp = "timeOff".$i;
			array_push($inactive, $_POST[$temp]);
			//$off = $_POST[$temp];
			//$on = array($on1,$on2);
			//$off = array($off1,$off2);

			
			// checks each of the day boxes
			$temp = "mon".$i;
			$monday = isset($_POST[$temp]);
			$temp = "tue".$i;
			$tuesday = isset($_POST[$temp]);
			$temp = "wed".$i;
			$wednesday = isset($_POST[$temp]);
			$temp = "thu".$i;
			$thursday = isset($_POST[$temp]);
			$temp = "fri".$i;
			$friday = isset($_POST[$temp]);
			$temp = "sat".$i;
			$saturday = isset($_POST[$temp]);
			$temp = "sun".$i;
			$sunday = isset($_POST[$temp]);

			// puts the data into a boolean array
			$daysArray = array($monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
			array_push($days, $daysArray);
			//$on = $on1;
			//$off = $off1;

			$temp = "state".$i;
			array_push($states, $_POST[$temp]);
			
		} // end of forloop
		
		//Get form data
		$formdata = Array (
			"0" => Array (
				"name" => $val
			)
		);

		// connection to MongoDB
    	$client = new MongoDB\Client("mongodb://localhost:27017");
    	$collection = $client->KlapLab->preferences;
		$query = ['dev_name' => $val];
    	$result = (array) $collection->findOne($query);

		$keys = array_keys((array)$result);

		//$bigActiveArray = 
		//for($i = 0; $i < $counter; $i++){

				
		//}

		//encode array to json
		$jsondata = json_encode(array('items' => $formdata),JSON_PRETTY_PRINT);
		//write json data into data.json file

		if(file_put_contents($myFile, $jsondata)) {
			echo 'Data successfully saved<br>';
			$link = '<a href="home.php">Return to homepage.</a>';

			// connect to MongoDB database 
			#$client = new MongoDB\Client("mongodb://localhost:27017");
			#$collection = $client->KlapLab->preferences;

			// delete old preferences
			//echo $val;
			$deleteResult = $collection->deleteMany(['dev_name' => $val]);
			printf("Deleted %d old preferences <br>", $deleteResult->getDeletedCount());


			// search devices table for the device ID
			$search = $client->KlapLab->devices;
			$query = ['name' => $val];
			$result = $search->findOne($query);

			$insert = $collection->insertOne([
				"device_object" => $result,
				"dev_name" => $val,
				"ON" => $active, 
				"OFF" => $inactive,
				"days" => $days
			]);

			echo $link;
		}
		else
			echo "error";


	} //end of try
	

	catch (Exception $e) {
		echo 'Caught exception: ', $e->getMessage(),"\n";
	}

	// reload preferences page
    header('Location: http://localhost/smarthomepreferenceapp/preferences.php');
?>

</body>
</html>