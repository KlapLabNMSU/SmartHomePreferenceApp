<!DOCTYPE html>
<!--
Author: Moinul Morshed Porag Chowdhury
Contributors: ---
Date Last Modified: 03/30/2022
Description: Installs selected binding to Openhab. Uninstalls passed value is not "install"
Includes: createfiles.php Item_handler.php nav-bar.php
Included In: --- 
Links To: scan.php
Links From: scan.php
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
<?php sleep(2);//give the site a chance to scan for devices?>

<?php include('nav-bar.php'); ?>
<?php echo "<script> document.getElementById('Register Devices').className += ' active';</script>"; ?>

<div class="container">
	<div class="jumbotron">
	  <h1>Binding operation status</h3>
	  <p>Check below the status of your binding operation.</p>
	</div>

    <?php 
        $_bindingId = $_POST['bindingId'];
		$boo_Install = $_POST['install'];
		//echo $boo_Install;
		$_code = "400";
		if($boo_Install == "install"){
			$_code = bindingInstall('localhost:8080','smarthome','smarthome',$_bindingId);
		}
		else {
			$_code = bindingUninstall('localhost:8080','smarthome','smarthome',$_bindingId);	
		}			
			
		if ($_code == "200"){
			echo "<div class='p-2'><h3>".$_bindingId." binding ".$boo_Install."ation successful!</h3></div>";	
		}
		else { 
			echo "<div class='p-2'><h3>".$_bindingId." binding ".$boo_Install."ation unsuccessful!</h3></div>";	
		}
    ?>

	<button class="btn btn-primary" type="button" onclick="location.href='scan.php'">Done</button>

    
</div>
</body>
</html>