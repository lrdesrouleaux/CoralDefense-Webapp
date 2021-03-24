<?php
$writefile=fopen("command_file.txt","w")or die("Unable to open file!");
$command=$_POST['command']or die("unable to init post");
fwrite($writefile,$command);
header("location: index.php")
?>
