<?php
$fname = $_GET['fName'];
$lname = $_GET['lName'];
$phone = $_GET['phone'];

$servername = "mysql.eecs.ku.edu";
$username = "kmittenburg";
$password = "P@\$\$word123";
$dbname = "kmittenburg";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO CUSTOMER (FIRST_NAME, LAST_NAME, PHONE)
VALUES('$fname', '$lname', '$phone')";
$conn->begin_transaction();
$result = $conn->query($sql);
$conn->commit();
echo $result;

$conn->close();
?>
