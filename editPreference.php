<!DOCTYPE html>
<!--
Author: Jacob Yoder
Contributors:
Date Last Modified: 09/21/2023
Description: Deletes the chosen preference and reloads preferences.php
-->
<html lang="en">
<head>
	<title>Smart Home Device Scheduler</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- make the corresponding navigation bar to active -->
<?php include('nav-bar.php'); 
    
require __DIR__ . '/vendor/autoload.php';
?>


<div class="jumbotron">
    <h2>Edit Device Preferences for "<?php ?>"</h2>
</div>';

<input class="btn btn-primary" type="submit" name="Submit" value="Save">
<button class="btn btn-primary" type="button" onclick="location.href='preferences.php'">Back</button>

<?php
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
        //$update = $collection->updateOne(
        //    ['_id' => new MongoDB\BSON\ObjectId($id)], 
        //    ['$set' => ['ON' => $newOnPrefs, 'OFF' => $newOffPrefs, 'days' => $newDaysPrefs]]);
        
        //printf("Matched %d document(s)\n", $update->getMatchedCount());
       // printf("Modified %d document(s)\n", $update->getModifiedCount());
    }

    // reload preferences page
    //header('Location: http://localhost/smarthomepreferenceapp/preferences.php');
    

	// delete old preferences
	//echo $id . "  " . $preference;
	//$deleteResult = $collection->deleteMany(['_id' => $id]);
	//printf("Deleted %d old preferences <br>", $deleteResult->getDeletedCount());

?>
</body>
</html>