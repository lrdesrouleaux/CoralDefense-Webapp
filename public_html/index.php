<?php
//This line will make the page auto-refresh every 15 seconds
$page = $_SERVER['PHP_SELF'];
$sec = "15";
?>


<html>
<head>
<!--//bootstrap for the tables, so I inport the CSS files as well...-->
<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
<!-- Latest CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>





<body style='background:linear-gradient(to right, #6600cc 0%, #33ccff 81%);'>




<?php

include("database_connect.php");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//table select
$result = mysqli_query($con,"SELECT * FROM livetable");


echo "<table class='table' style='background: linear-gradient(to right, #6600cc 0%, #33ccff 81%); font-size: 30px'>

    </style>
	<thead>
		<tr>
		<th style='color: rgb(255,255,255);'>Sensor Monitor</th>
		</tr>
	</thead>

    <tbody>
      <tr class='active'>
        <td style = 'border-right: solid 2px gray;'>Timeid</td>
        <td style = 'border-right: solid 2px gray;' align='center'>Temperature</td>
        <td style = 'border-right: solid 2px gray;' align='center'>Humidity</td>
        <td style = 'border-right: solid 2px gray;' align='center'>Flow Frequency</td>
		<td style = 'border-right: solid 2px gray;' align='center'>Flow Total ml</td>
		<td style = 'border-right: solid 2px gray;' align='center'>Flow Rate ml</td>
		<td style = 'border-right: solid 2px gray;' align='center'>Hall Effect State</td>
		<td style = 'border-right: solid 2px gray;' align='center'>Level Switch State</td>
		<td align='center'>UV State</td>
      </tr>";


$row = mysqli_fetch_array($result);

 	echo "<tr class='info'>";

	echo "<td style = 'border-right: solid 2px gray;' align='left' >" . $row['timeid'] . "</td>";
	echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['temperature'] . "</td>";
	echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['humidity'] . "</td>";
	echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['flow_frequency'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['flow_total_ml'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['flow_rate_ml'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['hall_effect_state'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['level_switch_state'] . "</td>";
    echo "<td style = 'border-right: solid 2px gray;' align='center'>" . $row['uv_state'] . "</td>";
	echo "</tr>
	</tbody>";



echo "</table>
";
?>

<?php
//We include the database_connect.php which has the data for the connection to the database
include("database_connect.php");

//Now we create the table with all the values from the database
echo "<table class='table' style='background: linear-gradient(to right, #6600cc 0%, #33ccff 81%); font-size: 30px'>
	<thead>
		<tr>
		<th style='color: rgb(255,255,255);'>Sensor Controls</th>
		</tr>
	</thead>

    <tbody>
      <tr class='active'>
        <td>Coral Defense ID</td>
        <td align='center'>Uv light</td>
        <td align='center'>Water Fill Pump</td>
        <td align='center'>Airpump</td>
		<td align='center'>BTF Light Strip</td>
        <td align='center'>Flow Meter</td>
      </tr>";

//loop through the table and print the data into the table
    echo"<tr>
    <td style = 'color: rgb(255,255,255);'>CID=9999</td>
    <td align='center'><form action=send_arduino_data.php method='post'>
    <input type='hidden' name='command' value='#112' size='15'>
    <input style='border-radius:10px 10px 10px 10px; background-color: lightgreen;' type='submit'value='on'></form>
    <form action=send_arduino_data.php method='post'>
    <input type='hidden' name='command' value='#113' size='15'>
    <input style='border-radius:10px 10px 10px 10px; background-color: red;' type='submit'value='off'></form>
    </td>
    <td align='center'><form action=update_values.php method='post'>
    <input type='hidden' name='command' value='#114' size='15'>
    <input style='border-radius:10px 10px 10px 10px; background-color: lightgreen;' type='submit' value='on'></form>
    <form action=update_values.php method='post'>
    <input type='hidden' name='command' value='#115' size='15'>
    <input style='border-radius:10px 10px 10px 10px; background-color: red;' type='submit' value='off'></form>
    </td>
    <td align='center'><form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: lightgreen;' type='submit'value='on'></form>
    <form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: red;' type='submit'value='off'></form>
    </td>
    <td align='center'><form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: lightgreen;' type='submit'value='on'></form>
    <form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: red;' type='submit'value='off'></form>
    </td>
    <td align='center'><form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: lightgreen;' type='submit'value='on'></form>
    <form action=update_values.php method='post'>
    <input style='border-radius:10px 10px 10px 10px; background-color: red;' type='submit'value='off'></form>
    </td>";

    echo"</tr>
</tbody>";

echo "</table>
";
?>

<?php
include("database_connect.php");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM ESPtable2");//table select

echo "<table class='table' style='font-size: 30px;'>

    <tbody>
      <tr class='active'>
        <td>Coral Defense ID</td>
        <td>Indicator 1</td>
        <td>Indicator 2 </td>
		<td>Indicator 3 </td>
      </tr>
		";



while($row = mysqli_fetch_array($result)) {

 	$cur_sent_bool_1 = $row['SENT_BOOL_1'];
	$cur_sent_bool_2 = $row['SENT_BOOL_2'];
	$cur_sent_bool_3 = $row['SENT_BOOL_3'];


	if($cur_sent_bool_1 == 1){
    $label_sent_bool_1 = "label-success";
	$text_sent_bool_1 = "Active";
	}
	else{
    $label_sent_bool_1 = "label-danger";
	$text_sent_bool_1 = "Inactive";
	}


	if($cur_sent_bool_2 == 1){
    $label_sent_bool_2 = "label-success";
	$text_sent_bool_2 = "Active";
	}
	else{
    $label_sent_bool_2 = "label-danger";
	$text_sent_bool_2 = "Inactive";
	}


	if($cur_sent_bool_3 == 1){
    $label_sent_bool_3 = "label-success";
	$text_sent_bool_3 = "Active";
	}
	else{
    $label_sent_bool_3 = "label-danger";
	$text_sent_bool_3 = "Inactive";
	}


	  echo "<tr class='info'>";
	  $unit_id = $row['id'];
        echo "<td>" . $row['id'] . "</td>";
		echo "<td>
		<span class='label $label_sent_bool_1'>"
			. $text_sent_bool_1 . "</td>
	    </span>";

		echo "<td>
		<span class='label $label_sent_bool_2'>"
			. $text_sent_bool_2 . "</td>
	    </span>";

		echo "<td>
		<span class='label $label_sent_bool_3'>"
			. $text_sent_bool_3 . "</td>
	    </span>";
	  echo "</tr>
	  </tbody>";



}
echo "</table>";
?>
