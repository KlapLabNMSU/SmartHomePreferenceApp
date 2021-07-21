<!DOCTYPE html>
<html>
<head>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Smart Home Device List</title>
</head>
<body>


<div class="container">
	<div class="jumbotron">
		<h1>Smart Home Device List</h1>
		<p>Welcome! Please select a device to edit it's preferences.</p>
	</div>
	<?php include 'createfiles.php'; ?>
	<?php print "Available devices:<br>"; ?>
	<div class="d-inline-flex p-2">
		<ul class="list-group">
		<?php foreach($arr_data as $items){ ?>
		   <li class="list-group-item"> <?php $link = '<a href="mywebpage2.php?val='; ?>
		   <?php $link .= $items.'">'.$items.'</a>'; ?> 
		   <?php print $link; ?> </li>
		<?php } ?>
		</ul>
	</div>
</div>
</body>
</html>
