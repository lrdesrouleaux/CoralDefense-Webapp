<!landing page for the web app!>
    <html>
    <title>Coral Defense</title>
    <link rel="shortcut icon" type="image/jpg" href="temp-favicon.jpg" />

    <head>
        <!--//I've used bootstrap for the tables imported via css-->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <!-- jQuery library for the javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>




    <!sets the background tone!>

        <body style='background:linear-gradient(to right, #6600cc 0%, #33ccff 81%);'>

            <?php
            include("connect_db.php");
            ?>
            <table class='table' style='background: linear-gradient(to right, #6600cc 0%, #33ccff 81%); font-size: 30px'>

                </style>
                <thead>
                    <tr>
                        <th style='color: rgb(255,255,255);'>Sensor Monitor</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class='active'>
                        <td style='border-right: solid 2px gray;'>Timeid</td>
                        <td style='border-right: solid 2px gray;' align='center'>Temperature</td>
                        <td style='border-right: solid 2px gray;' align='center'>Humidity</td>
                        <td style='border-right: solid 2px gray;' align='center'>Flow Total ml</td>
                        <td style='border-right: solid 2px gray;' align='center'>Hall Effect State</td>
                        <td style='border-right: solid 2px gray;' align='center'>Level Switch State</td>
                        <td align='center'>UV State</td>
                    </tr>
                    <!get the data from the server and put it into the row object then echo to the table!>
                        <?php $row = mysqli_fetch_array($result); ?>

                        <tr id="info" class='info'>

                            <td id="timeid" style='border-right: solid 2px gray;' align='left'>
                                <?php echo $row['timeid']; ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['temperature']; ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['humidity'] ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['flow_total_ml'] ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['hall_effect_state'] ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['level_switch_state'] ?> </td>
                            <td id="timeid" style='border-right: solid 2px gray;' align='center'>
                                <?php echo $row['uv_state'] ?> </td>
                        </tr>
                </tbody>
            </table>
            <!Js script that auto reloads the telemetry table!>
                <script type="text/javascript">
                    var table = $("#info");
                    $(document).ready(setInterval(function refreshdata() {
                        table.load("get_data.php");
                    }, 5000));
                </script>


                <table class='table' style='background: linear-gradient(to right, #6600cc 0%, #33ccff 81%); font-size: 30px'>
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
                        </tr>
                        <tr>
                            <td style='color: rgb(255,255,255);'>CID=9999</td>
                            <td align='center'>
                                <button id=uvoffclickindicator style='display:none'></button>
                                <button id=uvonclickindicator style='display:none'></button>
                                <button id="uv_on" style='border-radius:10px 10px 10px 10px; background-color: lightgreen; display:block'>on</button>&nbsp;
                                <button id="uv_off" style='border-radius:10px 10px 10px 10px; background-color: red; display:block'>off</button>
                            </td>
                            <!js script to send commands without page reload!>
                                <script type="text/javascript">
                                    var uvonbutton = $("#uv_on");
                                    var lastclicked = $("#uvonclickindicator");
                                    $(uvonbutton).click(function() {
                                        lastclicked.load("send_arduino_data.php", {
                                            command: "#112"
                                        });
                                    });
                                </script>

                                <!js script to send commands without page reload!>
                                    <script type="text/javascript">
                                        var uvoffbutton = $("#uv_off");
                                        var lastclicked = $("#uvoffclickindicator");
                                        $(uvoffbutton).click(function() {
                                            lastclicked.load("send_arduino_data.php", {
                                                command: "#113"
                                            });
                                        });
                                    </script>
                                    <td align='center'>
                                        <button id=wfoffclickindicator style='display:none'></button>
                                        <button id=wfonclickindicator style='display:none'></button>
                                        <button id="wf_on" style='border-radius:10px 10px 10px 10px; background-color: lightgreen; display:block'>on</button>&nbsp;
                                        <button id="wf_off" style='border-radius:10px 10px 10px 10px; background-color: red; display:block'>off</button>
                                    </td>
                                    <script type="text/javascript">
                                        var uvonbutton = $("#wf_on");
                                        var lastclicked = $("#wfonclickindicator");
                                        $(uvonbutton).click(function() {
                                            lastclicked.load("send_arduino_data.php", {
                                                command: "#114"
                                            });
                                        });
                                    </script>

                                    <!js script to send commands without page reload!>
                                        <script type="text/javascript">
                                            var uvoffbutton = $("#wf_off");
                                            var lastclicked = $("#wfoffclickindicator");
                                            $(uvoffbutton).click(function() {
                                                lastclicked.load("send_arduino_data.php", {
                                                    command: "#115"
                                                });
                                            });
                                        </script>
                                        <td align='center'>
                                            <button id=apoffclickindicator style='display:none'></button>
                                            <button id=aponclickindicator style='display:none'></button>
                                            <button id="ap_on" style='border-radius:10px 10px 10px 10px; background-color: lightgreen; display:block'>on</button>&nbsp;
                                            <button id="ap_off" style='border-radius:10px 10px 10px 10px; background-color: red; display:block'>off</button>
                                        </td>
                                        <script type="text/javascript">
                                            var uvonbutton = $("#ap_on");
                                            var lastclicked = $("#aponclickindicator");
                                            $(uvonbutton).click(function() {
                                                lastclicked.load("send_arduino_data.php", {
                                                    command: "#116"
                                                });
                                            });
                                        </script>

                                        <!js script to send commands without page reload!>
                                            <script type="text/javascript">
                                                var uvoffbutton = $("#ap_off");
                                                var lastclicked = $("#apoffclickindicator");
                                                $(uvoffbutton).click(function() {
                                                    lastclicked.load("send_arduino_data.php", {
                                                        command: "#117"
                                                    });
                                                });
                                            </script>
                                            <td align='center'>
                                                <button id=btfoffclickindicator style='display:none'></button>
                                                <button id=btfonclickindicator style='display:none'></button>
                                                <button id="btf_on" style='border-radius:10px 10px 10px 10px; background-color: lightgreen; display:block'>on</button>&nbsp;
                                                <button id="btf_off" style='border-radius:10px 10px 10px 10px; background-color: red; display:block'>off</button>
                                            </td>
                                            <script type="text/javascript">
                                                var uvonbutton = $("#btf_on");
                                                var lastclicked = $("#btfonclickindicator");
                                                $(uvonbutton).click(function() {
                                                    lastclicked.load("send_arduino_data.php", {
                                                        command: "#118"
                                                    });
                                                });
                                            </script>

                                            <!js script to send commands without page reload!>
                                                <script type="text/javascript">
                                                    var uvoffbutton = $("#btf_off");
                                                    var lastclicked = $("#btfoffclickindicator");
                                                    $(uvoffbutton).click(function() {
                                                        lastclicked.load("send_arduino_data.php", {
                                                            command: "#119"
                                                        });
                                                    });
                                                </script>
                                                <td align='center'>
                                                    <button id=fmoffclickindicator style='display:none'></button>
                                                    <button id=fmonclickindicator style='display:none'></button>
                                                    <button id="fm_on" style='border-radius:10px 10px 10px 10px; background-color: lightgreen; display:block'>on</button>&nbsp;
                                                    <button id="fm_off" style='border-radius:10px 10px 10px 10px; background-color: red; display:block'>off</button>
                                                </td>
                                                <script type="text/javascript">
                                                    var uvonbutton = $("#fm_on");
                                                    var lastclicked = $("#fmonclickindicator");
                                                    $(uvonbutton).click(function() {
                                                        lastclicked.load("send_arduino_data.php", {
                                                            command: "#120"
                                                        });
                                                    });
                                                </script>

                                                <!js script to send commands without page reload!>
                                                    <script type="text/javascript">
                                                        var uvoffbutton = $("#fm_off");
                                                        var lastclicked = $("#fmoffclickindicator");
                                                        $(uvoffbutton).click(function() {
                                                            lastclicked.load("send_arduino_data.php", {
                                                                command: "#121"
                                                            });
                                                        });
                                                    </script>
                        </tr>
                    </tbody>

                </table>

                <table class='table' style='font-size: 30px;'>

                    <tbody>
                        <tr class='active'>
                            <td align='left'>Coral Defense ID</td>
                            <td align='center'>Indicator 1</td>
                            <td align='center'>Indicator 2 </td>
                            <td align='center'>Indicator 3 </td>
                        </tr>
                        <tr>
                            <td style='color: rgb(255,255,255);'>CID=9999</td>
                            <td align='center'>
                                <button class="button button1" style="width: 250px; height: 50px; border-radius:10px 10px 10px 10px; border-style: outset; background-color: lightgreen;">indicator 1</button>
                            </td>
                            <td align='center'>
                                <button class="button button1" style="width: 250px; height: 50px; border-radius:10px 10px 10px 10px; border-style: outset; background-color: lightgreen;">indicator 2</button>
                            </td>
                            <td align='center'>
                                <button class="button button1" style="width: 250px; height: 50px; border-radius:10px 10px 10px 10px; border-style: outset; background-color: lightgreen;">indicator 3</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

    </html>
