<!--
Author: Miguel Fernandez, Theoderic Platt
Contributors: ---
Date Last Modified: 03/31/2022
Description: TODO
Includes: home.php
Included In: ---
Links To: ---
Links From: ---
-->
<?php
    chdir('/xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/');
    $numSchedules = 1;
    $numDevices = 2;
    $numTimes = 24;
    $command_exec = escapeshellcmd('python dep.py '.$numSchedules.' '.$numDevices.' '.$numTimes.'');
    $str_output = shell_exec($command_exec);
    echo $str_output;

    chdir('/xampp/htdocs/SmartHomePreferenceApp/');

    //include 'home.php';
?>