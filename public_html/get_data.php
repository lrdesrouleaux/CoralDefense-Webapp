<?php
include("connect_db.php");
$result = mysqli_query($con,"SELECT * FROM livetable");
$row = mysqli_fetch_array($result);
	echo "<td style = 'border-right: solid 2px gray;' align='left' >" . $row['timeid'] . "</td>";
	echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['temperature'] . "</td>";
	echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['humidity'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['flow_total_ml'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['hall_effect_state'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['level_switch_state'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['uv_state'] . "</td>";                    ?> 
