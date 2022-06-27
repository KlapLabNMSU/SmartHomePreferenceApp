<!--
Author: Miguel Fernandez
Contributors: ---
Date Last Modified: 03/31/2022
Description: TODO
Includes: ---
Included In: ---
Links To: ---
Links From: ---
-->

<?php
$writeToDB = false;

//This line will be needed in order to actually read the log files
chdir("E:/openHAB/userdata/logs");
$handle = fopen('events.log','r') or die ('File opening failed');
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
while (!feof($handle)) {
    $dd = fgets($handle);
    if((strpos($dd, "- Item") > 0) && (strpos($dd, "changed") > 0)){
        //Saves the date that the action happened
        $year = substr($dd, 0, 4);
        $month = substr($dd, 5, 2);
        $day = substr($dd, 8, 2);
        $date = $month."-".$day."-".$year;

        //Saves the time that it happened
        $hour = substr($dd, 11,2);
        $minute = substr($dd, 14, 2);
        $time = $hour.":".$minute;

        //Save item name
        $temp = substr($dd, strpos($dd, "- Item") + 8);
        $itemName = substr($temp, 0, strpos($temp, "'"));
        
        //Save item state change
        $temp = substr($temp, strpos($temp,"from") +5);
        $state1 = substr($temp, 0, strpos($temp, " "));
        $state2 = substr($temp, strpos($temp, "to") + 3, -1);
        $state2 = substr($state2, 0, strlen($state2) -1);

        echo "Date: ".$date."<br>";
        echo "Time: ".$time."<br>";
        echo "Item name: ".$itemName."<br>";
        echo "Action: ".$state1." to ".$state2."<br><br>";
        if($writeToDB){
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert(['name' => $itemName, 'date' => $date, 'time' => $time, 'state1' => $state1, 'state2' => $state2]);
            $manager->executeBulkWrite('KlapLab.logs', $bulk);
        }
    }
}