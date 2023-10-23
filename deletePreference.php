<!DOCTYPE html>
<!--
Author: Jacob Yoder
Contributors:
Date Last Modified: 09/21/2023
Description: Deletes the chosen preference and reloads preferences.php
-->
<html lang="en">
<body>
<?php
    require __DIR__ . '/vendor/autoload.php';

    echo "<h1>Deleting preference...<h1>";

    $id = $_POST['_id']; //Prefernce ID in mongoDB
    $num = $_POST['preference'];


    // connects to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->KlapLab->preferences;

    // grab the current device preferences
    //echo $id;
	$query = ['_id' => new MongoDB\BSON\ObjectId($id)];
	$dev = $collection->findOne($query);
    //echo $dev;

    // remove the selected preference
    if($dev == NULL){
        "Error device not found";
    }else{
        $newOnPrefs = (array)$dev["ON"];
        $newOffPrefs = (array)$dev["OFF"];
        $newDaysPrefs = (array)$dev["days"];
        unset($newOnPrefs[$num]);
        unset($newOffPrefs[$num]);
        unset($newDaysPrefs[$num]);
        $newOnPrefs = array_values($newOnPrefs);
        $newOffPrefs = array_values($newOffPrefs);
        $newDaysPrefs = array_values($newDaysPrefs);

        // updates Mongo with new Arrays for On and Off
        $update = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)], 
            ['$set' => ['ON' => $newOnPrefs, 'OFF' => $newOffPrefs, 'days' => $newDaysPrefs]]);
        
        printf("Matched %d document(s)\n", $update->getMatchedCount());
        printf("Modified %d document(s)\n", $update->getModifiedCount());
    }

    // reload preferences page
    header('Location: http://localhost/smarthomepreferenceapp/preferences.php');
    

	// delete old preferences
	//echo $id . "  " . $preference;
	//$deleteResult = $collection->deleteMany(['_id' => $id]);
	//printf("Deleted %d old preferences <br>", $deleteResult->getDeletedCount());

?>
</body>
</html>