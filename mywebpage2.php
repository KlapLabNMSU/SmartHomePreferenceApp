<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Device Preferences</title>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<?php include 'createfiles.php'; ?>

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
      <li class="nav-item">
        <a class="nav-link" href="scan.php">Scan</a>
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

<?php //This section is used to create the sections for users to put in preferences.?>
<?php $count = 2;?>
<div class="container">
	<?php $val = $_GET["val"]; ?>
	<div class="jumbotron">
		<h2><?php print $val ?> Device Preferences</h1>
	</div>
	<?//php include 'parsing.php'; ?>

	<form action="process.php" method="POST">
		<div class="input-group mb-3" style="border: groove; padding: 10px;">
			<div class="form-group w-50 mx-auto"> 
				<label for="exampleFormControlInput1"> Time On 1:</label>
				<input type="text" class="form-control" name="timeOn1" placeholder="12:00">
			</div>
			<div class="form-group w-50 mx-auto"> 
				<label for="exampleFormControlInput3"> Time Off 1:</label>
				<input type="text" class="form-control" name="timeOff1" placeholder="18:00">
			</div>
			
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
				<label class="form-check-label" for="inlineCheckbox1">Mon</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
			  <label class="form-check-label" for="inlineCheckbox2">Tue</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
			  <label class="form-check-label" for="inlineCheckbox3">Wed</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option1">
				<label class="form-check-label" for="inlineCheckbox1">Thr</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox5" value="option2">
			  <label class="form-check-label" for="inlineCheckbox2">Fri</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox6" value="option3">
			  <label class="form-check-label" for="inlineCheckbox3">Sat</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox7" value="option3">
			  <label class="form-check-label" for="inlineCheckbox3">Sun</label>
			</div>
		</div>
		
		<div class="input-group mb-3" style="border: groove; padding: 10px;">
			<div class="form-group w-50 mx-auto"> 
				<label for="exampleFormControlInput2"> Time On 2:</label>
				<input type="text" class="form-control" name="timeOn2" placeholder="12:00">
			</div>
			<div class="form-group w-50 mx-auto">
				<label for="exampleFormControlInput4"> Time Off 2:</label>
				<input type="text" class="form-control" name="timeOff2" placeholder="16:00">
			</div>
		</div>
	<?php $valPass='<input type="hidden" name="val" value="'.$val.'">';
	echo $valPass;?>

	<script>
		function addPref(){
		}
	</script>

	<button class="btn btn-primary" type="button" onclick="addPref()">Add new preference</button>
	<?php echo	'<input class="btn btn-primary" type="submit" name="Submit" value="Save">'; ?>
	<button class="btn btn-primary" type="button" onclick="location.href='home.php'">Back</button>
	
</form>

</div>
</body>
</html>
