<!--
Author: Miguel Fernandez
Contributors: ---
Date Last Modified: 03/31/2022
-->
<?php 
$lastRunLog = '/xampp/htdocs/SmartHomePreferenceApp/SHDS.php';
if (file_exists($lastRunLog)) {
    $lastRun = file_get_contents($lastRunLog);
    if (time() - $lastRun >= 86400) {
         //its been more than a day so run our external file
         $cron = file_get_contents('http://example.com/external/file.php');

         //update lastrun.log with current time
         file_put_contents($lastRunLog, time());
    }
}
?>