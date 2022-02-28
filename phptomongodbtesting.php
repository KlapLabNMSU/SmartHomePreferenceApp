<?php

    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    
    //This is used to delete all previous devices within the database :D
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['type' => 'Switch']);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['type' => 'Dimmer']);
    $result = $manager->executeBulkWrite('KlapLab.switch', $bulk);
    /*
    //This inserts our test devices into the code :D
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert(['name' => 'SP1_Power', 'type' => 'Switch', 'ON' => '12:20', 'OFF' => '13:42']);
    $bulk->insert(['name' => 'SB1_Dimmer', 'type' => 'Dimmer', 'ON' => '12:20', 'OFF' => '13:42']);
    $bulk->insert(['name' => 'TS1', 'type' => 'Switch', 'ON' => '12:20', 'OFF' => '13:42']);
    $manager->executeBulkWrite('KlapLab.switch', $bulk);
    /*
    $command = new MongoDB\Driver\Command([
        'aggregate' => 'switch',
        'pipeline' => [
            ['$group' => ['_id' => '$y', 'sum' => ['$sum' => '$x']]],
        ],
        'cursor' => new stdClass,
    ]);

    $cursor = $manager->executeCommand('KlapLab', $command);
    
    /* The aggregate command can optionally return its results in a cursor instead
     * of a single result document. In this case, we can iterate on the cursor
     * directly to access those results. */
    /*
    foreach ($cursor as $document) {
        var_dump($document);
    }*/
?>