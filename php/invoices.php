<?php
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

$sql = "SELECT PURCHASE.*, CUSTOMER.FIRST_NAME, CUSTOMER.LAST_NAME FROM PURCHASE, CUSTOMER
WHERE PURCHASE.CUSTOMER_ID = CUSTOMER.CUSTOMER_ID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
      $rows[] = $r;
    }
    echo json_encode($rows);
  }else {
    trigger_error('Invalid query: ' . $conn->error);
    echo "0 results";
}
$conn->close();
?>
