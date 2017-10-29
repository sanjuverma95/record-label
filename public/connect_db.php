<?php
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "recordlabel";

// Create connection
$conn = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);
// Check connection
if (mysqli_connect_errno()) {
   die("Connection failed: " . mysqli_connect_error());
} 
?>