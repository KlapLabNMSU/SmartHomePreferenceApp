<!DOCTYPE html>
<html>
<body>
<?php
    include 'Item_handler.php';

    $debug = false;

    $data = $_POST['itemData'];
    $name  = $_POST['name'];
    $label = $_POST['label'];
    $type  = $_POST['type'];

    $data = stripslashes($data);
    $data = json_decode($data,true);
    if($debug)echo 'name: '.$name.'</br>label: '.$label.'</br>type: '.$type.'</br>UID: '.array_values($data)[4];
    itemCreate('localhost:8080','smarthome','smarthome',$name,$label,$type,$debug);
    inboxApprove('localhost:8080','smarthome','smarthome',array_values($data)[4],$debug);
    itemLink('localhost:8080','smarthome','smarthome',$name,str_replace(':','%3A',array_values($data)[4]),strtolower($type),$debug);


    echo '<button class="btn btn-primary" type="button" onclick="location.href=\'home.php\'">Home</button>'



?>
</body>
</html>

