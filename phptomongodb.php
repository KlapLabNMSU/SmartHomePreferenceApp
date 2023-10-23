<?php
    //This code here is used to connect to MongoDB
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    //This section of code is being used to delete out old database info and write new information.)
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => $val]);
    //$result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

    //This section is used to delete NULL
<<<<<<< Updated upstream
    //*
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => NULL]);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

    //Delete NewItemTemp
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['name' => "NewItemTemp"]);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);//*/
=======
    if($debug){
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['name' => NULL]);
        //$result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

        //Delete NewItemTemp
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['name' => "NewItemTemp"]);
        //$result = $manager->executeBulkWrite('KlapLab.switch', $bulk);
    }
>>>>>>> Stashed changes

    //This code is being used to write a new switch to the database with the new preferences.
    $bulk = new MongoDB\Driver\BulkWrite;
    //$bulk->insert(['name' => $val, 'ON Day 1' => $on1, 'OFF Day 1' => $off1, 'ON Day 2' => $on2, 'OFF Day 2' => $off2]);
    //$manager->executeBulkWrite('KlapLab.switch', $bulk);
?>