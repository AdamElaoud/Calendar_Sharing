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
        if (isset($_GET['g_id'])) {
            $g_id = $_GET['g_id'];
            $query = "SELECT email FROM users_groups WHERE g_id = '$g_id'";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Select failed'.$dbConnection->error]);
                goto done;
            }
            $emails = castQueryResult($result);
            resJson(200, $emails);
        }
        break;

    case 'POST':
        if (isset($_POST['email']) && isset($_POST['g_id'])) {
            $email = $dbConnection->escape_string(trim($_POST['email']));
            $g_id = $_POST['g_id'];
            $query = "INSERT INTO users_groups (email, g_id) VALUES ('$email', '$g_id')";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Insertion failed: '.$dbConnection->error]);
                goto done;
            }
            resJson(201, [
                'email'=>$email,
                'g_id'=>$g_id
            ]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $delete_vars);
        if (true) {
            $g_id = $delete_vars['g_id'];
            $email = $_SESSION['user'];
            $query = "DELETE FROM users_groups WHERE email='$email' and g_id='$g_id'";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Delete failed'.$dbConnection->error]);
                goto done;
            }
            resJson(200);
        }
        break;
}

done:
$dbConnection->close();