<!DOCTYPE html>
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

<!-- make the corresponding navigation bar to active -->
<?php include('nav-bar.php'); ?>
<?php echo "<script> document.getElementById('scan').className += ' active';</script>"; ?>

<?php 
$data = $_POST['itemData'];
$itemData = json_decode($data,true);
var_dump($data) //TODO get the data to all correctly show
?>
<div class="container">
	<div class="jumbotron">
	  <h1>Register</h3>
	  <p>Please select an item from the list to add.</p>
	</div>

    <?php 
        
        
    ?>

	<button class="btn btn-primary" type="button" onclick="location.href='scan.php'">Back</button>

    
</div>
</body>
</html>