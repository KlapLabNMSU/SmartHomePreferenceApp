<html>
<body>

<?php

	$myFile = './SHDS/items/'."item3.json";
	$arr_data = array(); //create empty array
	$ON_arr = array();
	$OFF_arr = array();
	$directory = './SHDS/items/';
	//This is for future "reading file name" stuffs :)
	$scanned_directory = array_diff(scandir($directory),array('..','.'));
	foreach($scanned_directory as $values) {
		print_r(substr($values, 0, -5));
		print "<br>";
	}

	//This is to read file stuffs
	$homepage = file_get_contents($directory.'item1.json');
	print_r($homepage);
	print "<br>";

	//This is to try and find "ON" times
	//First, we find "ON"
	$homepager = $homepage;
	$indexOf = strpos($homepager, "ON", 0);
	$homepager = substr($homepager, $indexOf);
	//Find ON times
	$indexOf = strpos($homepager, "[", 0);
	$indexOf2 = strpos($homepager, "OFF", 0);
	$times = substr($homepager, $indexOf, $indexOf2-6);
	$indexOf2 = strpos($homepager, "]", 0);
	//Find times and put them in an array
	while($indexOf2 != 0 && $indexOf2 != 1){
		$indexOf = strpos($times, '"', 0);
		$times = substr($times, $indexOf+1);
		$indexOf = strpos($times, '"', 0);
		$timeToDis = substr($times, 0, $indexOf);
		//Push time to array
		array_push($ON_arr, $timeToDis);
		//Look for new time(s)
		$indexOf = strpos($times, '"', 0);
		$times = substr($times, $indexOf+1);
		//Finds end of times
		$indexOf2 = strpos($times, "]", 0);
	}
	for($x = 0; $x < 6; $x++)
		array_pop($ON_arr);
	print "On times in array: <br>";
	print_r($ON_arr);
	print "<br>";

	//Start finding off times
	$homepager = $homepage;
	$indexOf = strpos($homepager, "OFF", 0);
	$homepager = substr($homepager, $indexOf);
	//Find OFF times
	$indexOf = strpos($homepager, "[", 0);
	$indexOf2 = strpos($homepager, "]", 0);
	$times = substr($homepager, $indexOf, $indexOf2);
	//Find times and put them in an array
	while($indexOf2 != 0 && $indexOf2 != 1){
		$indexOf = strpos($times, '"', 0);
		$times = substr($times, $indexOf+1);
		$indexOf = strpos($times, '"', 0);
		$timeToDis = substr($times, 0, $indexOf);
		//Push time to array
		array_push($OFF_arr, $timeToDis);
		//Look for new time(s)
		$indexOf = strpos($times, '"', 0);
		$times = substr($times, $indexOf+1);
		//Find end of times
		$indexOf2 = strpos($times, "]", 0);
	}
	for($x = 0; $x < 6; $x++)
		array_pop($OFF_arr);
	print "Off times in array: <br>";
	print_r($OFF_arr);
	print "<br>";
?>

</body>
</html>
