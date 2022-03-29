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


<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="home.php">Smart Home Device Scheduler</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="schedule.php">Schedules</a>
      </li>
      <li class="nav-item  active">
        <a class="nav-link" href="scan.php">Register Devices</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Device Preferences
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
			<?php foreach($arr_data as $items){ ?>
				<?php $link = '<a class="dropdown-item" href="mywebpage2.php?val='; ?>
				<?php $link .= $items.'">'.$items.'</a>'; ?> 
				<?php print $link; ?>
			<?php } ?>
        </div>
      </li>
	        <li class="nav-item">
        <a class="nav-link" href="settings.php">Settings</a>
      </li>
    </ul>
  </div>
</nav>
<?php 
$data = $_POST['itemData'];
$dataS = stripslashes($data);
$dataEncoded = json_decode($dataS,true);
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