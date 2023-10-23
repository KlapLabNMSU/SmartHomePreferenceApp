<!DOCTYPE html>
<!--
Author: Diego Terrazas
Contributors: Jacob Yoder
Date Last Modified: 10/2/2023
Description: Shows user previously entered preferences for active devices
Includes: Item_handler.php
Included In: ---
Links To: ---
Links From: chat.php
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

<?php include('nav-bar.php'); ?>

<?php 
    require __DIR__ . '/vendor/autoload.php';
    #include 'item_handler.php';

    $prompt = $_POST['prompt'];

    $command = 'python chatcall.py "' . $prompt .'" ';
    $commandExec = escapeshellcmd($command);

    $str_output = shell_exec($commandExec); // magically works
    echo "<h1>And Mr.GPT said...</h1><br>";
    echo "<h2>".$str_output."</h1>";

    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->KlapLab->relational_preferences;

    $insert = $collection->insertOne(["pref" => $str_output]);
?>
<button onclick="goBack()"> Back to Home </button>

<script>
	function goBack(){
		window.location.href = "chat.php";
	}

</script>