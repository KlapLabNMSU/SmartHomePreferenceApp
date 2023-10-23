<!DOCTYPE html>
<!--
Author: Diego Terrazas
Contributors: Jacob Yoder
Date Last Modified: 08/21/2023
Description: Removes a given item
Includes: Item_handler.php
Included In: ---
Links To: activedevices.php
Links From: activedevices.php
-->
<html>
<body>
<?php

	include 'Item_handler.php';
	$name  = $_POST['name'];
	$thingName = "";

	require_once 'vendor/autoload.php';

	// connect to MongoDB database 
	$client = new MongoDB\Client("mongodb://localhost:27017");

	// Remove Item from OpenHab
	itemRemove('localhost:8080','smarthome','smarthome',$name);
	
	// Create a new cURL handle
	$ch = curl_init();
	// Set the URL of the OpenHAB REST API endpoint for getting items
	curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/rest/things/');

	// Set the request method to GET
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

	// Set the username and password for the OpenHAB user
	curl_setopt($ch, CURLOPT_USERPWD, 'smarthome:smarthome');

	// Set the return transfer option to true
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the request
	$response = curl_exec($ch);

	// Check the HTTP status code
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if ($httpCode == 200) {
	    // The request was successful
	    $json = json_decode($response);

	    // Get the thing name from the JSON object
	    foreach($json as $things){
			// removes all things that have don't have a item connected to it
			if(empty($things->channels[0]->linkedItems)){
				//echo $things->UID;
				removeThing('localhost:8080','smarthome','smarthome',$things->UID);
			}
		}
	} else {
	    // The request failed
	    echo 'The request failed with status code: ' . $httpCode;
	}

	// Close the cURL handle
	curl_close($ch);

	// Renive device and preferences from MongoDB
	$collection = $client->KlapLab->preferences;
	$deleteResult1 = $collection->deleteMany(['dev_name' => $name]);
	$collection = $client->KlapLab->devices;
	$deleteResult2 = $collection->deleteMany(['name' => $name]);

	// reload preferences page
    header('Location: http://localhost/smarthomepreferenceapp/activedevices.php');

?>
</body>