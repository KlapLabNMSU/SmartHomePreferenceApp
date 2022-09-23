<!DOCTYPE html>
<!--
Author: Theoderic Platt
Contributors: Moinul Morshed Porag Chowdhury
Date Last Modified: 04/19/2022
Description: Lists all items found in the inbox after scanning. Allows user to select item, linking to registration.php. 
Includes: createfiles.php Item_handler.php nav-bar.php
Included In: ---
Links To: scan.php items.php registration.php 
Links From: items.php scan.php
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
<?php echo "<script> document.getElementById('scan').className += ' active';</script>"; ?>



<div class="container">
	<div class="jumbotron">
	  <h1>Register Devices</h3>
	  <p>Please select a device to register.</p>
	  <div class="alert alert-warning" role="alert">If your item does not appear after scanning, you may need to wait a few seconds and refresh the page.</div>
	</div>

    <?php 
      $uid = $_POST['UID'];
      $scanner = [$uid];
      bindingScan('localhost:8080',$usr,$psd,$scanner);
      $jsondata = inboxList('localhost:8080',$usr,$psd);
      $items = array_values($jsondata);
      echo '<div class ="border border-primary p-2">';	
      
          echo '
          <div class="d-block p-2">
            <form method="post" action="registration.php">';
            foreach($items as $item){
              $data = htmlentities(json_encode($item));
              echo '
                <input type="hidden" name="itemData" value="'.$data.'"> 
                <button class="btn btn-primary" type="submit">
                    Type: '.array_values(array_values($item)[2])[11].'</br>
                    UID: '.array_values($item)[4].'
                </button> </br></br>';
              }
            echo '
            </form>
          </div>
          ';
      
      
      echo '</div>';
    ?>

    </br>
	<button class="btn btn-primary" type="button" onclick="location.href='scan.php'">Back</button>
	<button class="btn btn-primary" type="button" onclick="window.location.reload()">Refresh</button>
    <!--script type="text/javascript">
        alert('If your item does not appear after scanning, you may need to wait a few seconds and refresh the page.');
    </script-->
    
</div>
</body>
</html>