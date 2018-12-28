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
        header('Cache-Control: no-cache');
        $accepts = explode(',', $_SERVER['HTTP_ACCEPT']);
        $query = "SELECT id, name FROM users_groups, groups WHERE email = '$user' and g_id = id";
        $result = $dbConnection->query($query);
        if (!$result) {
            resJson(500, ['message' => $dbConnection->error]);
            goto done;
        }
        $group_ids = castQueryResult($result);
        resJson(200, $group_ids);
        break;

    case 'POST':
        if (isset($_POST['id'])) {
            $groupId = $_POST['id'];
            $name = $dbConnection->escape_string(trim($_POST['name']));
            $query = "UPDATE groups SET name='$name' WHERE id = '$groupId'";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Update failed: '.$dbConnection->error]);
                goto done;
            }
            resJson(200);
        } else {
            $query = "INSERT INTO groups (name) VALUES ('Untitled')";
            $dbConnection->query($query);
            $group_id = $dbConnection->insert_id;
            $query = "INSERT INTO users_groups (email, g_id) VALUES ('$user', '$group_id')";
            $result = $dbConnection->query($query);
            if (!$result) {
                resJson(500, ['message' => 'Insertion failed: '.$dbConnection->error]);
                goto done;
            }
            resJson(201, [
                'id' => $group_id,
                'name' => 'Untitled'
            ]);
        }
        break;
}

done:
$dbConnection->close();