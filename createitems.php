<!DOCTYPE html>
<!--
Author: Theoderic Platt
Contributors: Moinul Morshed Porag Chowdhury
Date Last Modified: 03/30/2021
Description: Takes the name, label, and type of an item along with its metadata and creates an item in Openhab.
             Creates a thing to control the item. Links item to thing.
Includes: Item_handler.php
Included In: ---
Links To: ---
Links From: registration.php
-->
<html>
<body>
<?php

    require __DIR__ . '/vendor/autoload.php';

    include 'Item_handler.php';

    // connection to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->KlapLab->devices;

    $debug = false;

    $data = $_POST['itemData'];
    $name  = $_POST['name'];
    $label = $_POST['label'];
    $loc = $_POST['location'];
    $type  = $_POST['type'];

    $data = stripslashes($data);
    $data = json_decode($data,true);

    $name = str_replace(array(' ' ,'\t', '\n', '\0', '\r', '\x0B'), '', $name);

    $query = ['name' => $name];
    $result = $collection->findOne($query);
    $counter = 0;
    $search = $client->KlapLab->devices;
    while ($result != NULL){
        $counter = $counter + 1;
        $tempname = $name . $counter;
        $query = ['name' => $tempname];
        $result = $search->findOne($query);
        echo $name.$counter.'      ';   
    }
    if($counter > 0){
        $name = $name . $counter;
    }
    if($debug)echo 'name: '.$name.'</br>label: '.$label.'</br>type: '.$type.'</br>UID: '.array_values($data)[4];
    
    itemCreate('localhost:8080','smarthome','smarthome',$name,$label,$type,$debug);
    inboxApprove('localhost:8080','smarthome','smarthome',array_values($data)[4],$debug);
    itemLink('localhost:8080','smarthome','smarthome',$name,str_replace(':','%3A',array_values($data)[4]),strtolower($type),$debug);

    


    header("Location: activedevices.php");

    
    
    $insert = $collection->insertOne([
                    
        "name" => $name,
        "label" => $label,
        "location" => $loc,
        "type" => $type

    ]);




    $api = 'localhost:8080/rest/channel-types';
    $cred = 'smarthome';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$api);
    curl_setopt($ch, CURLOPT_USERPWD, $cred . ":" . $cred);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    curl_close ($ch);

    $channels = json_decode($response, true);

    $states = array_values($channels);

    $stateCollection = $client->KlapLab->states;

    // search devices table for the device ID
    $search = $client->KlapLab->states;
    

    // Adds all posible states of the device to the States table
    foreach($states as $state){
        $UID = array_values($state)[8];
        $query = ['device_UID' => $UID];
        $result = $search->findOne($query);
        
        if($result == NULL){ // If state doesn't already exist in table add it
            if($UID != "true"){
                if($UID != false){   
                    $stateInsert = $stateCollection->insertOne([
                    "device_UID" => $UID,
                    "states" => array_values($state)[6]

                ]);

                }
            } // make sure to not add useless states
        } // make sure state isnt already in database
    }

    // search devices table for the device ID
    $search = $client->KlapLab->states;
    $thingUID = $data["thingTypeUID"];

    $query = ['device_UID' => $thingUID];
    $result = $search->findOne($query);

    $search = $client->KlapLab->devices;
	$query = ['name' => $name];
	$sresult = $search->findOne($query);

    $formdata = Array("dev_name" => $name, "device_object" => $sresult);

    if(is_object($result["states"]) ) {

        foreach($result["states"]["options"] as $state ){
            
            $formdata[$state->label] = Array();

        }

    }

    else{
        //If this segment of code is reached, it is assumed that the item 
        //that is being entered is a switch

        $formdata["ON"] = Array();    
        $formdata["OFF"] = Array();

    }
    $formdata["days"] = Array();


    
    $search = $client->KlapLab->preferences;
	$insert = $search->insertOne($formdata);



    $json = json_encode($formdata);
    file_put_contents("test.txt", $json);

    #$UID = array_values($state)[8];
    #$query = ['device_UID' => $UID];
    #$result = $search->findOne($query);

    #exit();

?>
</body>
</html>
