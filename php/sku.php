<?php
$sku = $_GET['sku'];
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

$sql = "SELECT * FROM PRODUCT WHERE SKU = '$sku'";
$conn->begin_transaction();
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
      $rows[] = $r;
    }
    $conn->commit();
    echo json_encode($rows);
  }else {
    $conn->rollback();
    trigger_error('Invalid query: ' . $conn->error);
    echo "0 results";
}
$conn->close();
?>
