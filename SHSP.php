<?php
    chdir('/xampp/htdocs/SmartHome/SHSP/ndvan-sg-a411f481df3f/tests/');

    $command_exec = escapeshellcmd('python dep.py 1 4 24');
    $str_output = shell_exec($command_exec);
    //echo $str_output;

    chdir('/xampp/htdocs/SmartHome/');

    include 'home.php';
?>