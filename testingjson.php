<html>
<body>

<?php
//array
$on = array("12:40","13:35");
$off =array("12:28","13:34");
$array = Array (
	"0" => Array (
		"name" => "SP1_Power",
		"ON" => $on,
		"OFF" => $off
	)
);

//encode array to json
$json = json_encode(array('items' => $array),JSON_PRETTY_PRINT);

//write json to file
if(file_put_contents('./SHDS/items/'."item3.json", $json))
	echo "JSON file created successfully...";
else
	echo "Oops! Error creating json file...";

// data.json

?>

</body>
</html>
