<!DOCTYPE html>
<!--
Author: Theoderic Platt
Contributors: ---
Date Last Modified: 04/19/2022
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

<div class="container">
	<div class="jumbotron">
	  <h1>Device Registration</h1>
	  <div class="alert alert-warning" role="alert">Please select a binding to scan for devices on that network/brand. If the binding is not present, you have to install that binding to register your device.</div>
	</div>
  <?php 
    $bindings = bindingList('localhost:8080','smarthome','smarthome');//FIXME ask porag how to get username/password data without hardcoding it.
	echo '<div class ="border border-primary p-2"><h3> Installed bindings </h3></div>';
	echo '<div class ="border border-primary">';
    foreach($bindings as $item){
      echo'<div class = "d-block p-2">
				<form class="d-inline" method="post" action="items.php">
					<input type="hidden" name="UID" value="'.$item.'">
					<button class="btn btn-primary" type="submit">'.substr($item,8).'</button>
				</form>
				<form class="d-inline" method="post" action="installbinding.php">
					<input type="hidden" name="install" value="uninstall">
					<input type="hidden" name="bindingId" value="'.$item.'">
					<button class="btn btn-primary" type="submit">Remove</button>
				</form>
			</div>'; 
    }
	echo '</div>';
  ?>

  </br>
  <button class="btn btn-primary" type="button" onclick="location.href='bindings.php'">Install new bindings</button>
	<button class="btn btn-primary" type="button" onclick="location.href='home.php'">Back</button>

</div>
</body>
</html>