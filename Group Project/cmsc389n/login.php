<?php
require 'scripts/utility.php';
session_start();

$username = '';
$password = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'scripts/dbConfig.php';

    $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbDatabase);
    if ($dbConnection->connect_error) {
        trigger_error('Database connection failed: '.$dbConnection->connect_error, E_USER_ERROR);
    }

    $action = $_POST['action'];
    $email = strtolower(trim($_POST['email'] ?? ''));
    $query = "select * from users where email = '$email'";
    $result = $dbConnection->query($query);
    if ($action === 'login') {
        if ($result->num_rows !== 0) {
            $resultPass = $result->fetch_row()[2];
            if ($resultPass === sha1($_POST['password'])) {
                $_SESSION['user'] = $email;
                header("location: main.php");
                exit;
            } else {
                echo "<script>alert('Incorrect credentials.')</script>";
            }
        } else {
            echo "<script>alert('No such user.')</script>";
        }
    } else if ($action === 'register') {
        if ($result->num_rows === 0) {
            $name = $_POST['name'];
            $password = sha1($_POST['password']);
            if ($password === sha1($_POST["confirmPassword"])) {
                $imgName = "assets/profile_pictures/default_pic.jpg";
          	    $imgMimeType = "image/jpg";
          	    $imgData = addslashes(file_get_contents($imgName));
                $query = "insert into users (
                    email,
                    name,
                    password,
                    imgName,
                    imgMimeType,
                    imgData
                ) values (
                    '$email',
                    '$name',
                    '$password',
                    '$imgName',
                    '$imgMimeType',
                    '$imgData'
                )";
                $result = $dbConnection->query($query);
                if (!$result) {
                    echo "ERROR : " . $dbConnection->error;
                }
                $dbConnection->commit();
                $_SESSION['user'] = $email;
                header("location: main.php");
                exit;
            } else {
                echo "<script>alert('Your passwords must match. Please enter the same password twice.')</script>";
            }
        } else {
            echo "<script>alert('An account with that email address already exists!')</script>";
        }
    }
}

render:
$body = <<<EOBODY
<div class="jumbotron">
    <h1 class="login-h1">Just Another Team Management App</h1>
</div>
<div class="row background">
    <div class="container col-sm-6 background">
        <div class="login-container">
            <h3 class="login-h1">Returning user? Log in here:</h3>
            <br />
            <h1>Log In</h1>
            <form id="login" method="post" action="{$_SERVER['PHP_SELF']}">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" class="text-box">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" class="text-box">
                </div>
                <br />
                <input type="hidden" name="action" value="login">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
        </div>
    </div>
    <div class="container col-sm-6 background">
        <div class="login-container">
            <h3 class="login-h1">New? Sign up for free!</h3>
            <br />
            <h1>Sign Up</h1>
            <form id="register" method="post" action="{$_SERVER['PHP_SELF']}">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Name" class="text-box">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" class="text-box">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" class="text-box">
                </div>
                <div class="form-group">
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" class="text-box">
                </div>
                <br />
                <input type="hidden" name="action" value="register">
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
        </div>
    </div>
</div>
EOBODY;

echo renderPage('JATMA - Login', $body);
