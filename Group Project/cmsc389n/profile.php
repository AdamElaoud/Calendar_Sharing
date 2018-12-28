<?php
// startup
require 'scripts/utility.php';

session_start();

require 'scripts/dbConfig.php';
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);

// collect user data
$email = $_SESSION['user'];
$query = "select * from users where email = '$email'";
$userArray = $dbConnection->query($query);
$user = $userArray->fetch_row()[1];

$body = <<<BODY
<div id = "profile-page" class = "background">
    <!-- Back Button -->
    <div id = "toolbar">
        <form action = "main.php">
            <input type = "submit" class="btn btn-primary login-h1" style="margin-top: 20px; margin-left: 20px" value = "Back"/>
        </form>
    </div>

    <!-- Picture -->
    <h3 class="center login-h1" id="profileHeader"></h3>
    <img src = "getImage.php" id = "pic"/>

    <!-- Update Profile -->
    <form id = "update" action = "profileConfirm.php" enctype = "multipart/form-data" method = "POST" class="login-h1">
        Edit Profile Picture: <input type = "file" name = "pic" accept = "image/*">
        <br />
        Edit Name: <input type = "text" name = "name" value = "$user" class="text-box" style="margin-top: 10px; margin-bottom: 30px">
        <br>
        <input type = "submit" value = "Confirm" name = "submit" class="btn btn-primary login-h1"/>
        <input type = "submit" value = "Cancel" formaction = "main.php" class="btn btn-primary login-h1"/>
        <br />
        <br />
        <br />
    </form>

    <h3 class = "center login-h1">$user's Calendar</h2>
        <br />

    <!-- Schedule -->
    <div class = "center">
        <!-- Monday -->
        <table class = "schedule" id = "monday">
            <tr>
                <th>Monday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('monday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Tuesday -->
        <table class = "schedule" id = "tuesday">
            <tr>
                <th>Tuesday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('tuesday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Wednesday -->
        <table class = "schedule" id = "wednesday">
            <tr>
                <th>Wednesday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('wednesday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Thursday -->
        <table class = "schedule" id = "thursday">
            <tr>
                <th>Thursday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('thursday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Friday -->
        <table class = "schedule" id = "friday">
            <tr>
                <th>Friday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('friday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Saturday -->
        <table class = "schedule" id = "saturday">
            <tr>
                <th>Saturday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('saturday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>

        <!-- Sunday -->
        <table class = "schedule" id = "sunday">
            <tr>
                <th>Sunday</th>
            </tr>
            <tr>
                <td>
                    <button onclick = "addEvent('sunday')" class="btn btn-primary login-h1">Add Event</button>
                </td>
            </tr>
        </table>
        <div class="row background" style="height: 50px; margin-top: 50px; margin-bottom: 50px">
        </div>
    </div>

</div>

BODY;


echo renderpage("$user", $body);
?>
