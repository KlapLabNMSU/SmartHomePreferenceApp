<html>
<body>

<?php
//array
$array = Array (
	"0" => Array (
		"name" => $values,
		"ON" => "",
		"OFF" => ""
	)
);

//encode array to json
$json = json_encode(array('items' => $array),JSON_PRETTY_PRINT);

//write json to file
if(file_put_contents('./SHDS/items/'.$values.".json", $json))
	echo "JSON file created successfully...";
else
	echo "Oops! Error creating json file...";

// data.json

?>

</body>
</html>
