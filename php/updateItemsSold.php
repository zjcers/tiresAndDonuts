<?php
$orderId = $_GET['orderId'];
$orig_sku = $_GET['orig_sku'];
$orig_quan = $_GET['orig_quan'];
$new_sku = $_GET['new_sku'];
$new_quan = $_GET['new_quan'];

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

$sql = "UPDATE SOLDITEMS
SET SKU = '$new_sku', AMOUNT = '$new_quan'
WHERE PURCHASE_ID = '$orderId' AND SKU = '$orig_sku' AND AMOUNT = '$orig_quan'";

$conn->begin_transaction();
$result = $conn->query($sql);

$sql1 = "UPDATE PURCHASE SET AMOUNT = (SELECT SUM(PRODUCT.PRICE*SOLDITEMS.AMOUNT) FROM SOLDITEMS, PRODUCT WHERE SOLDITEMS.PURCHASE_ID='$orderId'
AND SOLDITEMS.SKU = PRODUCT.SKU)
WHERE PURCHASE_ID = '$orderId'";
$result1 = $conn->query($sql1);
if($result1){
  $conn->commit();
}
else{
  $conn->rollback();
}
echo $result1;


echo $result;

$conn->close();
?>
