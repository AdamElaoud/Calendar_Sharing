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

if(isset($_POST["name"])) {
  $nameValue = trim($_POST["name"]);

  $sqlQuery1 = "update users set name='$nameValue' where email='$user'";
  $result1 = $dbConnection->query($sqlQuery1);

  if (!$result1) {
    die("Failed to change name: " . $dbConnection->error);
  }
}

if(isset($_POST["submit"])) {
  if($_FILES["pic"]["tmp_name"] != null) {
    $fileToInsert = $_FILES["pic"]["name"];
    $docMimeType = $_FILES["pic"]["type"];
  	$fileData = addslashes(file_get_contents($_FILES["pic"]["tmp_name"]));

    $sqlQuery2 = "update users set imgName='$fileToInsert', imgMimeType='$docMimeType', imgData='$fileData' where email='$user'";
  	$result2 = $dbConnection->query($sqlQuery2);
  	if (!$result2) {
  		echo "<h3 style='font-style: italic'>Failed to change profile picture to $fileToInsert</h3>";
  	}
    else {
      echo "<img src=\"getImage.php\" width=\"240\" height=\"240\"/><br>";
    }
  }
}

$dbConnection->close();
echo "Your profile has been updated.<br>";
echo '<a class="btn btn-primary login-h1" href="main.php">Home</a><br>';
echo '<a class="btn btn-primary login-h1" href="logout.php">Sign Out</a>';
