<?php

function renderPage($title, $body) {
    $base = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        return <<<EOPAGE
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>$title</title>
    <base href="$base/" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/styles.css" />
    <link rel="stylesheet" href="assets/test.css" />
    <link rel="stylesheet" href="assets/login.css" />
    <link rel="stylesheet" href="assets/profile_styles.css" />
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src = "scripts/profile.js"></script>
$body
</body>
</html>
EOPAGE;
}

function resJson($status = 200, $body = null) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($body);
}

function castQueryResult($result) {
    $fields = $result->fetch_fields();
    $data = [];
    $types = [];
    foreach ($fields as $field) {
        switch ($field->type) {
        case MYSQLI_TYPE_BIT:
        case MYSQLI_TYPE_TINY:
        case MYSQLI_TYPE_SHORT:
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
        case MYSQLI_TYPE_INT24:
        case MYSQLI_TYPE_YEAR:
        case MYSQLI_TYPE_ENUM:
            $types[$field->name] = 'int';
            break;

        case MYSQLI_TYPE_DECIMAL:
        case MYSQLI_TYPE_NEWDECIMAL:
        case MYSQLI_TYPE_FLOAT:
        case MYSQLI_TYPE_DOUBLE:
            $types[$field->name] = 'float';
            break;

        default:
            $types[$field->name] = 'string';
            break;
        }
    }
    while ($row = $result->fetch_assoc()) array_push($data, $row);
    for ($i = 0; $i < count($data); $i++) {
        foreach ($types as $name => $type) settype($data[$i][$name], $type);
    }
    return $data;
}
