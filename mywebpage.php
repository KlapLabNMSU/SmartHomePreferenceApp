<hmtl>
<body>
<?php
$val = $_GET["val"];
include 'parsing.php';
?>
<form action="process.php" method="POST">
<?php $header=	'Name of Device: '.$val.'<br>';
echo $header;
?>
echo	'<br><br/>

echo	'Time On:<br>
echo	'<input type="text" name="timeOn1">
echo	'<br><br>
echo	'Time On:<br>
echo	'<input type="text" name="timeOn2">
echo	'<br><br>

echo	'Time Off:<br>
echo	'<input type="text" name="timeOff1">
echo	'<br><br>
echo	'Time Off:<br>
echo	'<input type="text" name="timeOff2" value="1:32">
echo	'<br><br>
$valPass =   '<input type="hidden" name="val" value="
$valPass .= $val.'">
echo $valPass;
echo	'<input type="submit" name="Submit">
?>
	<button type="button" onclick="location.href='homepage.php'">Back</button>
<?php
echo'</form>';

?>
</body>
</html>
