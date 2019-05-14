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

$sql = "SELECT PURCHASE.PURCHASE_ID, FIRST_NAME, LAST_NAME, DATETIME, TOTALS.TOTAL AS AMOUNT
FROM PURCHASE, CUSTOMER,
  (SELECT PURCHASE.PURCHASE_ID, SUM(PRODUCT.PRICE * SOLDITEMS.AMOUNT) AS TOTAL
  FROM PURCHASE, SOLDITEMS, PRODUCT
  WHERE PURCHASE.PURCHASE_ID = SOLDITEMS.PURCHASE_ID
  AND SOLDITEMS.SKU = PRODUCT.SKU
  GROUP BY PURCHASE.PURCHASE_ID) AS TOTALS
WHERE PURCHASE.PURCHASE_ID = TOTALS.PURCHASE_ID
AND PURCHASE.CUSTOMER_ID = CUSTOMER.CUSTOMER_ID;";
$conn->begin_transaction();
$conn->query('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;');
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
      $rows[] = $r;
    }
    $conn->commit();
    echo json_encode($rows);
  }else {
    trigger_error('Invalid query: ' . $conn->error);
    echo "0 results";
}
$conn->close();
?>
