<?php
require '../scripts/utility.php';
require '../scripts/dbConfig.php';
session_start();

if (!isset($_SESSION['user'])) {
    resJson(401);
    exit;
} else {
    $user = $_SESSION['user'];
}

$dbConnection = @new mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);
if ($dbConnection->connect_error) {
    resJson(500, ['message' => $dbConnection->connect_error]);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['email'])) {
            $user = $_GET['email'];
        } else {
            $user = $_SESSION['user'];
        }
        $query = "SELECT name, schedule FROM users WHERE email = '$user'";
        $result = $dbConnection->query($query);
        if (!$result) {
            resJson(500, ['message' => $dbConnection->error]);
            goto done;
        }
        $schedule = castQueryResult($result);
        resJson(200, $schedule);
        break;

    case 'POST':
        if (isset($_POST['schedule'])) {
            $user = $_SESSION['user'];
            $schedule = json_encode($_POST['schedule']);
            $query = "UPDATE users SET schedule='$schedule' WHERE email = '$user'";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Update failed: '.$dbConnection->error]);
                goto done;
            }
            resJson(200, $result);
        }
        break;
}

done:
$dbConnection->close();
