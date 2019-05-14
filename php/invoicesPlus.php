<?php
$orderId = $_GET['orderId'];
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
function getName($sql) {
  global $orderId;
  $query = "SELECT FIRST_NAME, LAST_NAME
    FROM CUSTOMER, PURCHASE
    WHERE PURCHASE_ID = $orderId
    AND CUSTOMER.CUSTOMER_ID = PURCHASE.CUSTOMER_ID";
  if ($result = $sql->query($query)) {
    if ($result->num_rows == 1) {
      $name = $result->fetch_assoc();
      $result->close();
      return $name;
    }
    var_dump($result);
  }
  die("Could not retrieve name for customer");
}

$output = getName($conn);

$sql = "SELECT PRODUCT.*, SOLDITEMS.AMOUNT, PURCHASE.AMOUNT AS TOTAL
FROM PURCHASE, SOLDITEMS, PRODUCT
WHERE PURCHASE.PURCHASE_ID = '$orderId'
AND PURCHASE.PURCHASE_ID = SOLDITEMS.PURCHASE_ID
AND SOLDITEMS.SKU = PRODUCT.SKU";
$conn->begin_transaction();
$result = $conn->query($sql);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
  $rows[] = $r;
}
$result->close();
$output["items"] = $rows;
echo json_encode($output);
$conn->close();
?>
