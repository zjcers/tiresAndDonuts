<?php
$name = $_GET['name'];
$inventory = $_GET['inventory'];
$price = $_GET['price'];
$type = $_GET['type'];
$model = $_GET['model'];
$manf = $_GET['manf'];
$size = $_GET['size'];


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
if($type === "Tire"){
$sql = "INSERT INTO PRODUCT (NAME, INVENTORY, PRICE, PRODUCT_TYPE, TIRE_MODEL, TIRE_MANF, TIRE_SIZE)
VALUES('$name', '$inventory', '$price', '$type', '$model', '$manf', '$size')";
}
else{
  $sql = "INSERT INTO PRODUCT (NAME, INVENTORY, PRICE, PRODUCT_TYPE, TIRE_MODEL, TIRE_MANF, TIRE_SIZE)
  VALUES('$name', '$inventory', '$price', '$type', NULL, NULL, NULL)";
}
$conn->begin_transaction();
$result = $conn->query($sql);
if($result){
  $conn->commit();
}
else{
  $conn->rollback();
}
echo $result;

$conn->close();
?>
