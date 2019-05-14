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
$conn->begin_transaction();
$conn->query('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;');
$sql = "DELETE FROM PURCHASE WHERE PURCHASE_ID = '$orderId'";

$result = $conn->query($sql);
if($result){
  $sql2 = "DELETE FROM SOLDITEMS WHERE PURCHASE_ID = '$orderId'";
  $result2 = $conn->query($sql2);
  if($result2){
    $conn->commit();
  }
  else {
    $conn->roll_back();
  }
  echo $result2;
}
else{
  echo $result;
}

$conn->close();
?>
