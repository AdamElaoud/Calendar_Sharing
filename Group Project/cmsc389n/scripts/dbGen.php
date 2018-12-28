<?php
require 'dbConfig.php';

$rootuser = "root";
$rootPassword = "";

$table_groups = "groups";
$table_users = "users";
$table_users_groups = "users_groups";

$connection = new mysqli($dbHost, $rootuser, $rootPassword);
$result = '';

if ($connection->connect_error) {
    $result .= "connection failed: ".$connection->connect_error."<br>";
} else {
    if ($connection->query("drop database $dbDatabase")) {
        $result .= "Dropped database $dbDatabase<br>";
    }
    if (!$connection->query("create database $dbDatabase")) {
        $result .= "Could not create database $dbDatabase: ".$connection->error."<br>";
    } else {
        $result .= "Database $dbDatabase was successfully created<br>";
    }

    if (!$connection->query("create user $dbUser@localhost identified by '$dbPassword'")) {
        $result .= "Failed to create user $dbUser: ".$connection->error."<br>";
    } else {
        $result .= "Created user $dbUser<br>";
    }

    if (!$connection->query("grant all privileges on $dbDatabase . * to $dbUser@localhost")) {
        $result .= "Failed to grant user $dbUser privileges to $dbDatabase: ".$connection->error."<br>";
    } else {
        $connection->query("flush privileges");
        $result .= "Granted user $dbUser privileges to $dbDatabase<br>";
    }

    if (!$connection->query("use $dbDatabase")) {
        $result .= "Failed to use database $dbDatabase: ".$connection->error."<br>";
    } else {
        $result .= "Using database $dbDatabase<br>";
        $command = "create table $table_users (
            email varchar(64) not null primary key,
            name varchar(64) not null,
            password varchar(64) not null,
            schedule LONGTEXT,
            imgName LONGTEXT,
            imgMimeType varchar(512),
            imgData longblob
        )";
        if (!$connection->query($command)) {
            $result .= "Could not create table $table_users: ".$connection->error."<br>";
        } else {
            $result .= "Created table $table_users<br>";
        }

        $command = "create table $table_groups (
            id int(7) unsigned auto_increment primary key,
            name varchar(128) not null
        )";
        if (!$connection->query($command)) {
            $result .= "Could not create table $table_groups: ".$connection->error."<br>";
        } else {
            $result .= "Created table $table_groups<br>";
        }

        $command = "create table $table_users_groups (
            email varchar(64),
            g_id int(7)
        )";
        if (!$connection->query($command)) {
            $result .= "Could not create table $table_users_groups: ".$connection->error."<br>";
        } else {
            $result .= "Created table $table_users_groups<br>";
        }
    }
    $connection->close();
}

$result .= "---EOF---";
echo $result;
