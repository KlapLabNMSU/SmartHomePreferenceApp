<!DOCTYPE html>
<html>
<body>

<?php
<<<<<<< Updated upstream
date_default_timezone_set("America/Denver");
$hour = date("h");
$minute = date("i");
$second = date("s");
$military = date("a");
$currentTime = $hour*100+$minute;
=======
require __DIR__ . '/vendor/autoload.php';

$switch = true;
$tz = "America/Denver";
date_default_timezone_set($tz);
echo("type ctrl-C to stop the process\n");
echo("Time zone set to ".$tz);
echo("\nRunning SHDS indefinitely.\n");

updateSchedule(); // Call Scheduler for first time setup
while(true){
    sleep(1);
    $hour = date("h");
    $minute = date("i");
    $second = date("s");
    $military = date("a");
    $currentTime = $hour*100+$minute;
    if($minute == 0 && $second == 0){
        // Update schedule every hour
        updateSchedule();
    }
    if($second == 30 && !$switch)
        $switch = true;
    //($switch && $second == 00)
    if($switch && $second == 00){

        $switch == false;
        echo("Current Time: ".$hour.":".$minute.":".$second."\n");
>>>>>>> Stashed changes

//This code gets us the current time and converts it into military
if($hour == 12 && $military == "am"){
    $hour = 0;
}
else if($military == "pm" && $hour != 12){
    $hour = $hour + 12;
}

//This code will be used to read all items currently within the database and display them on the screen (testing purposes only right now)
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$filter  = [];
$options = ['sort'=>array('_id'=>-1),'limit'=>3]; # limit -1 from newest to oldest
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $manager->executeQuery('KlapLab.switch', $query); // $mongo contains the connection object to MongoDB


$nameArray = array();
$onTimesArray = array();
$onTemp = array();
$offTimesArray = array();
$offTemp = array();
foreach($rows as $r){

    $rowsArray = (array) $r;
    $json = json_encode($rowsArray);
    //echo $json."<br>";

    //This is used to find the name of the device
    $index = strpos($json, "name");
    $name = substr($json, $index+7);
    $index = strpos($name, "\"");
    $name = substr($name, 0, $index);
    array_push($nameArray, $name);
    //echo "Name: ".$name."<br>";


    //Clear variables
    $onTemp = array();
    $offTemp = array();
    //This is used to find all ON times
    if((strpos($json, "ON")+4) == strpos($json, "[")){
        $index = strpos($json, "ON") + 6;
        $temp = $json;
        $on = substr($temp, $index);
        while(strpos($on, "\"OFF\"") != 0){
            $index = strpos($on, "\"");
            $temp = $on;
            $on = substr($on, 0, $index);
            $index += 3;
            array_push($onTemp, $on);
            $on = substr($temp, $index);
        }
        array_push($onTimesArray, $onTemp);
    }
    else{
        $index = strpos($json, "ON") + 5;
        $on = substr($json, $index);
        $index = strpos($on, "\"");
        $on = substr($on, 0, $index);
        array_push($onTimesArray, $on);
    }
    //This is used to find all OFF times
    $index = strpos($json, "OFF");
    $temp = substr($json, $index);
    if((strpos($temp, "OFF")+5) == strpos($temp, "[")){
        $index = strpos($json, "OFF") + 7;
        $temp = $json;
        $off = substr($temp, $index);
        while(strlen($off) > 0){
            $index = strpos($off, "\"");
            $temp = $off;
            $off = substr($off, 0, $index);
            $index += 3;
            array_push($offTemp, $off);
            $off = substr($temp, $index);
        }
        array_push($offTimesArray, $offTemp);
    }
    else{
        $index = strpos($json, "OFF") + 6;
        $off = substr($json, $index);
        $index = strpos($off, "\"");
        $off = substr($off, 0, $index);
        array_push($offTimesArray, $off);
    }
    
}

<<<<<<< Updated upstream
//This section will be used to run the program every minute and compare 
//current time with preference time
$compare = $hour.":".$minute;
$i = 0;
foreach($onTimesArray as $times){
    $name = $nameArray[$i];
    foreach($times as $t)
        if($compare == $t)
            exec("curl --header \"Content-Type: text/plain\" --request POST --data \"ON\" http://localhost:8080/rest/items/".$name);
    $i += 1;
}

$i = 0;
foreach($offTimesArray as $times){
    $name = $nameArray[$i];
    foreach($times as $t)
        if($compare == $t)
            exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/".$name);
    $i += 1;
}

//This code is used to send POST requests to our devices which can turn them ON and OFF
//exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/SP1_Power");
=======
        //runExecute($hour, $minute, $second);
        //runExecuteJSON($hour, $minute, $second);
        runExecuteMongo($hour, $minute, $second);
    }//end if
}//end while


function updateSchedule() {
    echo("Calling Scheduler Builder...\n");
    $params = " 1 5 1440";
    //echo("In UpdateSchedule\n");
    //$genInst = shell_exec("python /xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/generate_instances.py" . $params);
    //$command = escapeshellcmd('python /xampp/htdocs/SmartHomePreferenceApp/SHSP/ndvan-sg-a411f481df3f/tests/dep.py');

    $command = escapeshellcmd('python /xampp/htdocs/SmartHomePreferenceApp/schedule_setup.py');
    //$com = ($command . $params);
    $com = $command;
    echo($com . "\n\n");

    $out = shell_exec($com);
    echo($out);
}

function runExecuteMongo($hour, $minute, $second){
    // connection to MongoDB
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->KlapLab->preferences;

    $result = $collection->find();
    foreach($result as $item){   
        
        $keys = array_keys((array)$item);
        foreach($keys as $key){
            
            if($key != "dev_name" && $key != "_id" && $key != "device_object" && $key != "days"){
                $len = count($item[$key]);
                for($i = 0; $i < $len; $i++){

                    if( !is_null($item[$key][$i]) ){
                        $compare = $hour.":".$minute;
                        $time = $item[$key][$i];
                        echo("this is to compare ". $compare ." \n");
                        
                        if($compare == $time) {
                            echo("curl --header \"Content-Type: text/plain\" --request POST --data \"".$key."\" http://localhost:8080/rest/items/".$item['dev_name'] ." \n");
                            exec("curl --header \"Content-Type: text/plain\" --request POST --data \"".$key."\" http://localhost:8080/rest/items/".$item['dev_name'] , $dump);
                            echo($item['dev_name'] . " is now ". $key ."!\n\n");
                        }

                    }

                }/* end looking through contents of each key */

            }/*end if statement exclude dev_name, _id, device_object, and days */

        }/* end looking through each key */

    }/* end looking through entire query loop */

}/* end runExecuteMongo */



function runExecuteJSON($hour, $minute, $second){
    $onTimesArray = array();
    $offTimesArray = array();

    $dirPath = 'E:/xampp/htdocs/SmartHomePreferenceApp/SHDS/items';
    if ($handle = opendir($dirPath)){
        while (false != ($file = readdir($handle))) {

            if($file != "." && $file != ".."){

                $json = file_get_contents($dirPath . "/" . $file);
                $jsonData = json_decode($json, true);
                $JSONitems = $jsonData['items'][0];
                //print_r($itemss['name']);

                array_push($nameArray, $JSONitems['name']);
                array_push($onTimesArray, $JSONitems['ON'][0]);
                array_push($offTimesArray, $JSONitems['OFF'][0]);
            }
        }
    }

    if(!is_null($onTimesArray) && !is_null($offTimesArray))
    {
        //This section will be used to run the program every minute and compare 
        //current time with preference time
        $compare = $hour.":".$minute;
        $i = 0;
        foreach($onTimesArray as $time){
            $name = $nameArray[$i];

            if($compare == $time) {
                exec("curl --header \"Content-Type: text/plain\" --request POST --data \"ON\" http://localhost:8080/rest/items/".$name , $dump);
                echo("Turned " . $name . " on!\n\n");
            }
            $i += 1;
        }

        $i = 0;
        foreach((array)$offTimesArray as $time){
            $name = $nameArray[$i];
            
            if($compare == $time) {
                exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/".$name , $dump);
                echo("Turned " . $name . " off!\n\n");
            }
            
            $i += 1;
        }
    }

}

function runExecute($hour, $minute, $second) {
        

        //This code will be used to read all items currently within the database and display them on the screen (testing purposes only right now)
        $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $filter  = [];
        $options = ['sort'=>array('_id'=>-1),'limit'=>3]; # limit -1 from newest to oldest
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $manager->executeQuery('KlapLab.switch', $query); // $mongo contains the connection object to MongoDB

        $nameArray = array();
        $onTimesArray = array();
        $onTemp = array();
        $offTimesArray = array();
        $offTemp = array();
        foreach($rows as $r){

            $rowsArray = (array) $r;
            $json = json_encode($rowsArray);
            //echo $json."\n";
            //print_r($json);

            //This is used to find the name of the device
            $index = strpos($json, "name");
            $name = substr($json, $index+7);
            $index = strpos($name, "\"");
            $name = substr($name, 0, $index);

            
            array_push($nameArray, $name);


            //Clear variables
            $onTemp = array();
            $offTemp = array();
            //This is used to find all ON times
            if((strpos($json, "ON")+4) == strpos($json, "[")){
                $index = strpos($json, "ON") + 6;
                $temp = $json;
                $on = substr($temp, $index);
                while(strpos($on, "\"OFF\"") != 0){
                    $index = strpos($on, "\"");
                    $temp = $on;
                    $on = substr($on, 0, $index);
                    $index += 3;
                    array_push($onTemp, $on);
                    $on = substr($temp, $index);
                }
                array_push($onTimesArray, $onTemp);
            }
            else{
                $index = strpos($json, "ON") + 5;
                $on = substr($json, $index);
                $index = strpos($on, "\"");
                $on = substr($on, 0, $index);
                array_push($onTimesArray, $on);
            }
            //This is used to find all OFF times
            $index = strpos($json, "OFF");
            $temp = substr($json, $index);
            if((strpos($temp, "OFF")+5) == strpos($temp, "[")){
                $index = strpos($json, "OFF") + 7;
                $temp = $json;
                $off = substr($temp, $index);
                while(strlen($off) > 0){
                    $index = strpos($off, "\"");
                    $temp = $off;
                    $off = substr($off, 0, $index);
                    $index += 3;
                    array_push($offTemp, $off);
                    $off = substr($temp, $index);
                }
                array_push($offTimesArray, $offTemp);
            }
            else{
                $index = strpos($json, "OFF") + 6;
                $off = substr($json, $index);
                $index = strpos($off, "\"");
                $off = substr($off, 0, $index);
                array_push($offTimesArray, $off);
            }
            
        }
        

        if(!is_null($onTimesArray) && !is_null($offTimesArray))
        {
            
            //This section will be used to run the program every minute and compare 
            //current time with preference time
            $compare = $hour.":".$minute;
            $i = 0;
            foreach($onTimesArray as $times){
                $name = $nameArray[$i];
                //echo($name . " is on at time: ".  implode(" ", (array)$times) . "\n");
                foreach((array)$times as $t)
                    if($compare == $t) {
                        exec("curl --header \"Content-Type: text/plain\" --request POST --data \"ON\" http://localhost:8080/rest/items/".$name , $dump);
                        echo("Turned " . $name . " on!\n\n");
                    }
                $i += 1;
            }

            $i = 0;
            foreach((array)$offTimesArray as $times){
                $name = $nameArray[$i];
                    foreach((array)$times as $t)
                        if($compare == $t) {
                            exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/".$name , $dump);
                            echo("Turned " . $name . " off!\n\n");
                        }
                    $i += 1;
            }
        }
        //This code is used to send POST requests to our devices which can turn them ON and OFF
        //exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/SP1_Power");
>>>>>>> Stashed changes

}
?>

</body>
</html>