<!--
Author: Miguel Fernandez
Contributors: ---
Date Last Modified: 03/24/2022
Description: TODO
Includes: ---
Included In: process.php
Links To: ---
Links From: ---
-->
<?php
    //This code here is used to connect to MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    //This section of code is being used to delete out old database info and write new information.)
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => $val]);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

    //This section is used to delete NULL
    //*
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => NULL]);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

    //Delete NewItemTemp
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => "NewItemTemp"]);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);//*/

    //This code is being used to write a new switch to the database with the new preferences.
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert(['name' => $val, 'ON' => $on, 'OFF' => $off]);
    $manager->executeBulkWrite('KlapLab.switch', $bulk);
?>