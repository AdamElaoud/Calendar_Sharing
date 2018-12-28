<?php
    session_start();
    session_unset();
    header('Refresh: 0; URL = main.php');