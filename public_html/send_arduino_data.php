<?php
/*this script writes to the command file*/
$writefile=fopen("command_file.txt","w")or die("Unable to open file!");
$command=$_POST['command']or die("unable to init post");
fwrite($writefile,$command);
?>