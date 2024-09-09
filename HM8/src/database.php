<?php

namespace HM8;

require 'dbconfig.php';

$servername = $databaseConfig['host'];
$username = $databaseConfig['username'];
$password = $databaseConfig['password'];
$dbname = $databaseConfig['dbname'];

$conn = new \MySQLi($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
}
