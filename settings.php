<!DOCTYPE html>
<!--
Author: Moinul Morshed Porag Chowdhury
Contributors: ---
Date Last Modified: 03/30/2022
Description: Allows user to set the correct url to Scan OpenHAB for new devices and set path to store the json files.
Includes: createfiles.php nav-bar.php
Included In: ---
Links To: home.php
Links From: ---
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

<!-- make the corresponding navigation bar to active -->
<?php include('nav-bar.php'); ?>
<?php echo "<script> document.getElementById('settings').className += ' active';</script>"; ?>


<div class="container">
	<div class="jumbotron">
	  <h1>Smart Home Device Scheduler Settings</h3>
	  <p>Please set the correct url to Scan OpenHAB for new devices and set path to store the json files.</p>
	</div>
	<form>
	  <div class="form-group">
		<label for="exampleInputEmail1">OpenHAB URL</label>
		<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter OpenHAB URL">
	  </div>
	  <div class="form-group">
		<label for="exampleInputEmail2">Preference Storage</label>
		<input type="text" class="form-control" id="exampleInputEmail2" placeholder="JSON_FILES/">
	  </div>
	  
	  <button type="submit" value="Save" class="btn btn-primary">Save</button>
	  <button class="btn btn-primary" type="button" onclick="location.href='home.php'">Back</button>
	</form>
</div>
</body>
</html>