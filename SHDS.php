<!DOCTYPE html>
<html>
<body>

<?php
date_default_timezone_set("America/Denver");
$hour = date("h");
$minute = date("i");
$second = date("s");
$military = date("a");
$currentTime = $hour*100+$minute;

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
    echo $json."<br>";

    //This is used to find the name of the device
    $index = strpos($json, "name");
    $name = substr($json, $index+7);
    $index = strpos($name, "\"");
    $name = substr($name, 0, $index);
    array_push($nameArray, $name);
    echo "Name: ".$name."<br>";


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
foreach($onTimesArray as $times)
    foreach($times as $t)
        echo "Time on:".$t."<br>";

foreach($offTimesArray as $times)
    foreach($times as $t)
        echo "Time off:".$t."<br>";

//This code is used to send POST requests to our devices which can turn them ON and OFF
exec("curl --header \"Content-Type: text/plain\" --request POST --data \"OFF\" http://localhost:8080/rest/items/SP1_Power");

?>

</body>
</html>