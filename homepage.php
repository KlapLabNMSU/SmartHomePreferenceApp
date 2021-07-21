
<html>
<body>

<?php

print "Welcome. Please select a device to edit preferences on:<br>";
include 'createfiles.php';
foreach($arr_data as $items){
   print '<br>';
   $link = '<a href="mywebpage.php?val=';
   $link .= $items.'">'.$items.'</a>';
   print $link;
}









print "<br><br>End of device list.";

?>

</body>
</html>
