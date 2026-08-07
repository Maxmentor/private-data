<?php
$host = "localhost";
$user = "root"; // aapka db username
$pass = ""; // aapka db password
$dbname = "private_data";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>