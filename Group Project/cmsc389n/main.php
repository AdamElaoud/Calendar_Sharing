<?php
require 'scripts/utility.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
require 'scripts/dbConfig.php';
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);
$query = "select * from users where email = '$user'";
$result = $dbConnection->query($query);
$name = $result->fetch_row()[1];
$body = <<<EOT
<div class="main-view background">
    <div class="group-list-container pull-left">
        <!-- Profile Page -->

        <button class="btn btn-primary btn-lg btn-block">+ Create Group</button>
        <ul class="list-group" id="groupListView">
        </ul>
    </div>

    <div class="container" id="rightSide" style="display: inline-block">
        <div class="group-view-container">
            <div class="row">
                <h1 class='login-h1'>Welcome $name!</h1>
            </div>

            <h1 id="groupNameHeader"></h1>
            <div class="form-group" style="margin-top: 125px">
                <div class="row">
                    <input type="text" class="form-control text-box" id="name" placeHolder="Group Name" style="width: 300px">
                    <button type="submit" class="btn btn-primary" id="submitNameChangeButton" style="margin-left: 10px">Submit</button>
                </div>
                <br />
                <div class="row">
                    <input type="text" class="form-control text-box" id="email" placeHolder="Add a member via email" style="width: 300px">
                    <button type="submit" class=" btn btn-primary" id="addGroupMemberButton" style="margin-left: 10px; width: 75px">Add</button>
                </div>
                <br />
            </div>
            <div class="members-list">
                <ul id="groupMembers" class="group-members">
                </ul>
            </div>
            <button class="btn btn-danger" id="leaveGroupButton">Leave Group</button>

            <br />
            <br />
            <br />
            <br />

            <ul id = "group-schedules">
                <!--Schedules Here -->
            </ul>
        </div>
        <form class="float-right" action = "profile.php">
            <input type = "submit" class="btn btn-primary" style="margin-right: 4px" value = "My Profile"/>
            <a class="btn btn-primary float-right" href="logout.php">Sign Out</a>
        </form>
    </div>
</div>
<script type="application/javascript" src="assets/main.js"></script>
EOT;

echo renderPage('JATMA', $body);
