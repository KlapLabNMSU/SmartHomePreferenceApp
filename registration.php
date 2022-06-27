<!DOCTYPE html>
<!--
Author: Theoderic Platt
Contributors: ---
Date Last Modified: 03/30/2022
Description: Prompts user for Name, Label, and Type of item. Submit links to createitems.php
Includes: createfiles.php Item_handler.php nav-bar.php
Included In: --- 
Links To: createitems.php
Links From: items.php
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

<?php include 'createfiles.php'; ?>
<?php include 'Item_handler.php';?>


<!-- make the corresponding navigation bar to active -->
<?php include('nav-bar.php'); ?>
<?php echo "<script> document.getElementById('scan').className += ' active';</script>"; ?>

<?php 
$data = $_POST['itemData'];
$dataS = stripslashes($data);
$dataEncoded = json_decode($dataS,true);
//echo "<div class ='border border-primary'>".$dataEncoded."</div>";
?>
<div class="container">
	<div class="jumbotron">
        
	  <h1>Please fill the following fields for '<?php echo array_values(array_values($dataEncoded)[2])[11];?>'.</h1>
      <p>Name: the name you wish to call your item.</p>
      <p>Label: the label you want you item to have.</p>
      <p>Type: select what type of item you have.</p>
	</div>
    
    <form name="form" action="createitems.php" method="post"><h4>Name</h4>
        <input type="hidden" name="itemData" value='<?php echo $data?>'>
        <input type="text" name="name" id="subject" value=""></br><h4>Label</h4>
        <input type="text" name="label" id="subject" value=""></br><h4>Type</h4>
        <select name="type">
        <option value="Color">Color</option>
        <option value="Contact">Contact</option>
        <option value="DateTime">DateTime</option>
        <option value="Dimmer">Dimmer</option>
        <option value="Group">Group</option>
        <option value="Image">Image</option>
        <option value="Location">Location</option>
        <option value="Number">Number</option>
        <option value="Player">Player</option>
        <option value="Rollershutter">Rollershutter</option>
        <option value="String">String</option>
        <option value="Switch">Switch</option>
    </select></br></br>
    <button class="btn btn-primary" type="submit">Submit</button> 
    <button class="btn btn-primary" type="button" onclick="location.href='scan.php'">Cancel</button>
    </form>


	

    
</div>
</body>
</html>