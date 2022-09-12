<!--
Author: Miguel Fernandez
Contributors: Theoderic Platt
Date Last Modified: 03/31/2022
Description: TODO
Includes: home.php Item_Handler.php
Included In: ---
Links To: ---
Links From: ---
-->
<?php include 'Item_handler.php';?>

<?php

    chdir('/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/');
    $numSchedules = 1; //generate 1 schedule
    $numDevices = sizeof(getAllItems('localhost:8080','smarthome','smarthome')); //generate for the number of devices on the system
    $numTimes = 1440; //generate 1440 unique time slots (there are 1440 minutes in a day)
    $command_exec = escapeshellcmd('python generate_instances.py '.$numSchedules.' '.$numDevices.' '.$numTimes.'');
    $command_exec = escapeshellcmd('python dep.py '.$numSchedules.' '.$numDevices.' '.$numTimes.'');
    $str_output = shell_exec($command_exec);
    echo $str_output;

    chdir('/xampp/htdocs/SmartHomePreferenceApp/');

    //include 'home.php';
?>