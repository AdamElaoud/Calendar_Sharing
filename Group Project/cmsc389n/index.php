<?php
require 'scripts/utility.php';
session_start();

$body = <<<EOT
<div class="jumbotron landing-header">
    <div class="container">
        <h1 class="login-h1">Just Another Team Management App</h1>
    </div>
</div>
<div class="background">
	<div class="container">
		<br />
		<h2 class='login-h1'>Getting Started</h2>
		<p class='login-h1'>
			Just Another Team Management App (JATMA) is meant to help you and your 
			teammates coordinate and schedule meetings. It lets you upload and share 
			your event load among your peers, and see what times are optimal for each 
			of your groups. To register or log in with our system, click the button below!
		</p>
EOT;
if (!isset($_SESSION['user'])) 
	$body .= '<a class="btn btn-primary" href="login.php">Sign In</a>';
else 
	$body .= '<a class="btn btn-primary" href="main.php">Go!</a>';
$body .= <<<EOT
	</div>
	<div class="container">
		<br />
		<br />
		<h2 class='login-h1'>Our Team</h2>
	    <p class='login-h1'>Alex Honer, Adam Elaoud, Wajiha Iftikhar, Anmol Srivastava</p>
	</div>
</div>
EOT;

echo renderPage('JATMA - Home', $body);