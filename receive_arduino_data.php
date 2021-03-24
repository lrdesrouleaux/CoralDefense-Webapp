<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$con=mysqli_connect("localhost","id16116035_coraldefense","Password123$","id16116035_coraldefensedb");
if (!$con) {
          die("Connection failed: " . mysqli_connect_error());
   }
$myfile = fopen("newfile.txt", "a");
$temperature=$_REQUEST['temperature'];
$humidity=$_REQUEST['humidity'];
$flow_frequency=$_REQUEST['flow_frequency'];
$flow_total_ml=$_REQUEST['flow_total_ml'];
$flow_rate_ml=$_REQUEST['flow_rate_ml'];
$hall_effect_state=$_REQUEST['hall_effect_state'];
$level_switch_state=$_REQUEST['level_switch_state'];
$uv_state=$_REQUEST['uv_state'];
fwrite($myfile,$uv_state);
$sql="INSERT INTO ESPtable1 (timeid,temperature,humidity,flow_frequency,flow_total_ml,flow_rate_ml,hall_effect_state,level_switch_state,uv_state) VALUES (current_timestamp(),$temperature,$humidity,$flow_frequency,$flow_total_ml,$flow_rate_ml,$hall_effect_state,$level_switch_state,$uv_state)";
fwrite($myfile,time());
fwrite($myfile,$sql);
mysqli_query($con,$sql) or die("no new entry made esptable");
 mysqli_query($con,"UPDATE livetable SET timeid = current_timestamp() WHERE findid=1")or die("timewrite error livetable");
$updatesql="UPDATE livetable SET timeid=current_timestamp(), temperature=$temperature,humidity=$humidity,flow_frequency=$flow_frequency,flow_total_ml=$flow_total_ml,flow_rate_ml=$flow_rate_ml,hall_effect_state=$hall_effect_state,level_switch_state=$level_switch_state,uv_state=$uv_state WHERE findid=1";
mysqli_query($con,$updatesql)or die("livetable update error");

?>
