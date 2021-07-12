<!DOCTYPE html>
<html>
<body>

<?php
$output = "";

exec("javac testfile.java", $output);
exec("java testfile", $output);
exec("rm -rf testfile.class", $output);
exec("rm -rf testfile", $output);
?>

</body>
</html>
