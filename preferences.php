<!DOCTYPE html>
<!--
Author: Diego Terrazas
Contributors: Jacob Yoder
Date Last Modified: 09/14/2023
Description: Shows user previously entered preferences for active devices
Includes: Item_handler.php
Included In: ---
Links To: activedevices.php
Links From: activedevices.php
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
    require __DIR__ . '/vendor/autoload.php';?>




<?php 
    $items = array_values(getAllItems('localhost:8080','smarthome','smarthome'));
    //echo '<div class ="border border-primary">';	
    
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->KlapLab->preferences;

    $result = $collection->find();
      
    // create table for each device
    foreach($result as $item){
        echo '<table style="width:60%" align = "center" border = "2" cellpadding = "3" cellspacing = "0">';
        echo '<tr>  
        <th style="width:40%">'.$item['dev_name'].'</th>  
        <th>Preferences</th>';
        
        $keys = array_keys((array)$item);
        
        foreach($keys as $key){
            
            if($key != "dev_name" && $key != "_id" && $key != "device_object" && $key != "days"){
                $len = count($item[$key]);
                

                // adds row for each preference in each device
                for($i = 0; $i < $len; $i++){
                    echo '<tr>
                    <td>'.($i + 1).'</td>
                    <td>'.$key.' At '.$item[$key][$i].'</td>
                    <form method="post" action="deletePreference.php">
                        <input type="hidden" name="preference" value="'.$i.'"> 
                        <td><button class="btn btn-danger" name="_id" value="'.$item['_id'].'" type="submit">Delete</button></td>
                    </form>';

                    echo '<form method="post" action="editPreference.php">
                        <input type="hidden" name="preference" value="'.$i.'"> 
                        <td><button class="btn btn-primary" name="_id" value="'.$item['_id'].'" type="submit">Edit</button></td>
                    </form>';
        
                }
            }
        }
        // Add button
        echo '</tr><tr></tr>
            <form method="post" action="mywebpage2.php?val='.$item['dev_name'].'">
            <td><button class="btn btn-primary" type="submit">Add</button></td>
            </table><br>';

        
    }
?>

</body>

</html>
    
        
