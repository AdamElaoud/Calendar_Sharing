<?php
require 'scripts/utility.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
require 'scripts/dbConfig.php';
$dbConnection = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase);
$query = "select imgData, imgMimeType from users where email = '$user'";
$result = mysqli_query($dbConnection, $query);

if ($result) {
  $recordArray = mysqli_fetch_assoc($result);
  header("Content-type: " . "{$recordArray["imgMimeType"]}");
  echo $recordArray["imgData"];
  mysqli_free_result($result);
}
else {
  echo "Failed to retrieve document $fileToRetrieve: " . mysqli_error($dbConnection);
}

/* Closing */
mysqli_close($dbConnection);
// echo generatePage($body);
