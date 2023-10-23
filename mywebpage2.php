<!DOCTYPE html>
<!--
Author: Moinul Morshed Porag Chowdhury, Miguel Fernandez
Contributors: Jacob Yoder, Diego Terrazas 
Date Last Modified: 03/31/2022
Description: TODO
Includes: createfiles.php nav-bar.php
Included In: ---
Links To: process.php home.php
Links From: activedevices.php
-->
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
<script> var counter = 2; </script>

<?php include ('createfiles.php'); ?>
<?php include('nav-bar.php'); ?>
<?php echo "<script> document.getElementById('mywebpage2').className += ' active';</script>"; ?>

<?php //This section is used to create the sections for users to put in preferences. badass comment?>

<?php $count = 2;?>
<div class="container">
	<?php $val = $_GET["val"]; ?>
	<div class="jumbotron">
		<h2>Device Preferences for "<?php print $val ?>"</h1>
	</div>
	<?//php include 'parsing.php'; ?>

	<form action="process.php" method="POST" id="bigForm">

	<input class="btn btn-primary" type="submit" name="Submit" value="Save">
	<button class="btn btn-primary" type="button" onclick="addPref()">Add Preference</button>
	<button class="btn btn-primary" type="button" onclick="location.href='preferences.php'">Back</button>
	<br><br>
		

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
				<input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="mon1">
				<label class="form-check-label" for="inlineCheckbox1">Mon</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="tue1">
			  <label class="form-check-label" for="inlineCheckbox2">Tue</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox3" name="wed1">
			  <label class="form-check-label" for="inlineCheckbox3">Wed</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="inlineCheckbox4" name="thu1">
				<label class="form-check-label" for="inlineCheckbox4">Thr</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox5" name="fri1">
			  <label class="form-check-label" for="inlineCheckbox5">Fri</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox6" name="sat1">
			  <label class="form-check-label" for="inlineCheckbox6">Sat</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" type="checkbox" id="inlineCheckbox7" name="sun1">
			  <label class="form-check-label" for="inlineCheckbox7">Sun</label>
			</div>

			<select name="state">
				<?php
					require __DIR__ . '/vendor/autoload.php';

					$client = new MongoDB\Client("mongodb://localhost:27017");
    				$collection = $client->KlapLab->preferences;

					$query = ['dev_name' => $val];
        			$states = (array) $collection->findOne($query);
				
					$keys = array_keys($states);

					foreach($keys as $key){
						if($key != "dev_name" && $key != "_id" && $key != "device_object" && $key != "days"){

							echo "<option value=\"".$key."\" >".$key."</option>";

						}

					}
				
				?>
			</select>

		</div>
		
	<?php echo '<input type="hidden" name="val" value="'.$val.'">'; ?>

	</form>
</div>

<script>
	function addPref(){
		var field = document.createElement("div");
		

		field.innerHTML = "<div class=\"input-group mb-3\" style=\"border: groove; padding: 10px;>"+
			"<input name=\"counterForm\" type=\"hidden\" value=\"" + counter + "\">" +
			"<div class=\"form-group w-50 mx-auto\">"+
			"<label for=\"exampleFormControlInput2\"> Time On " + counter + ":</label>"+
			"<input type=\"text\" class=\"form-control\" name=\"timeOn" + counter + "\" placeholder=\"12:00\">"+
			"</div> <div class=\"form-group w-50 mx-auto\"> <label for=\"exampleFormControlInput4\"> Time Off " + counter + ":</label>"+
				"<input type=\"text\" class=\"form-control\" name=\"timeOff" + counter + "\" placeholder=\"16:00\"> </div> <div class=\"form-check form-check-inline\">	"+
				"<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "1\" name=\"mon" + counter + "\">"+
				"<label class=\"form-check-label\" for=\"inlineCheckbox1\">Mon</label> </div> <div class=\"form-check form-check-inline\">"+
				"<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "2\" name=\"tue" + counter + "\">"+
			"<label class=\"form-check-label\" for=\"inlineCheckbox" + counter + "\">Tue</label>"+
			"</div> <div class=\"form-check form-check-inline\"> <input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "3\" name=\"wed" + counter + "\">"+
			"<label class=\"form-check-label\" for=\"inlineCheckbox3\">Wed</label>"+
			"</div>"+
			"<div class=\"form-check form-check-inline\">"+
				"<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "4\" name=\"thu" + counter + "\">"+
				"<label class=\"form-check-label\" for=\"inlineCheckbox4\">Thr</label>"+
			"</div>"+
			"<div class=\"form-check form-check-inline\">"+
			  "<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "5\" name=\"fri" + counter + "\">"+
			  "<label class=\"form-check-label\" for=\"inlineCheckbox5\">Fri</label>"+
			"</div>"+
			"<div class=\"form-check form-check-inline\">"+
			  "<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "6\" name=\"sat" + counter + "\">"+
			  "<label class=\"form-check-label\" for=\"inlineCheckbox6\">Sat</label>"+
			"</div>"+
			"<div class=\"form-check form-check-inline\">"+
			  "<input class=\"form-check-input\" type=\"checkbox\" id=\"inlineCheckbox" + counter + "7\" name=\"sun" + counter + "\">"+
			  "<label class=\"form-check-label\" for=\"inlineCheckbox7\">Sun</label>"+
			"</div>"+

			"</div>";
		

		document.getElementById("bigForm").appendChild(field);
		counter++;
	}

</script>

</body>



</html>
