<?php
$servername = "mysql.eecs.ku.edu";
$username = "kmittenburg";
$password = "P@\$\$word123";
$dbname = "kmittenburg";

function error($reason, $code = 400)
{
    http_response_code($code);
    echo($reason);
    exit(0);
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    error("Connection failed: " . $conn->connect_error);
}
function checkField($fieldName) {
    if (!array_key_exists($fieldName, $_POST)) {
        var_dump($_POST);
        error("Expected to find key: " . $fieldName);
    }
}
function validateRequest() {
    $REQUIRED_KEYS = array("FIRST_NAME", "LAST_NAME");
    foreach ($REQUIRED_KEYS as $key => $value) {
        checkField($value);
    }
}
function getCustomerId($sql) {
    $first = $sql->escape_string($_POST["FIRST_NAME"]);
    $last = $sql->escape_string($_POST["LAST_NAME"]);
    $query = "SELECT CUSTOMER_ID FROM CUSTOMER WHERE FIRST_NAME=\"$first\" AND LAST_NAME = \"$last\";";
    if ($result = $sql->query($query)) {
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $cust = $row["CUSTOMER_ID"];
            $result->close();
            return $cust;
        }
    }
    error("Could not get the ID of " . $first . " " . $last);
}
function hasAllKeys() {
    $KEYS = array("SKU", "AMOUNT");
    $hasKey = false;
    foreach ($KEYS as $key) {
        if (array_key_exists($key, $_POST)) {
            $hasKey = true;
        } else if ($hasKey) {
            error("Form did not contain key ". $key);
        }
    }
    return $hasKey;
}
function allKeysAreSameLength() {
    $KEYS = array("SKU", "AMOUNT");
    $knownLength = null;
    foreach ($KEYS as $key) {
        if (is_null($knownLength)) {
            $knownLength = count($_POST[$key]);
        } else if ($knownLength != count($_POST[$key])) {
            error("Expect there to be " . $knownLength . " of key " . $key);
        }
    }
    return true;
}
function insertItems($sql, $purchaseId) {
    if (hasAllKeys() && allKeysAreSameLength()) {
        foreach ($_POST["AMOUNT"] as $index => $amount) {
            $amount = $sql->escape_string($amount);
            $sku = $sql->escape_string($_POST["SKU"][$index]);
            $query = "INSERT INTO SOLDITEMS (PURCHASE_ID, SKU, AMOUNT) VALUES ($purchaseId, $sku, $amount);";
            if (!$sql->query($query)) {
                error("Could not insert sold item: " . $sql->error);
            }
        }
    }
}
function createNewInvoice($sql) {
    validateRequest();
    $sql->begin_transaction();
    $custId = getCustomerId($sql);
    $insertQuery = "INSERT INTO PURCHASE (CUSTOMER_ID) VALUES ($custId)";
    if (!$sql->query($insertQuery)) {
        error("Could not insert purchase: " . $sql->error);
    }
    $purchaseId = $sql->insert_id;
    insertItems($sql, $purchaseId);
    $sql->commit();
}
function updateInvoice($sql) {
    validateRequest();
    // It would be preferable to do UPDATEs here instead of DELETE/INSERT, but
    // that would require the UI to diff what it sends us
    $sql->begin_transaction();
    $purchaseId = $sql->escape_string($_POST["orderId"]);
    $deleteQuery = "DELETE FROM SOLDITEMS WHERE PURCHASE_ID = $purchaseId;";
    if (!$sql->query($deleteQuery)) {
        error("Could not delete old records for purchase: " . $purchaseId . " " . $sql->error);
    }
    insertItems($sql, $purchaseId);
    $sql->commit();
}

if (array_key_exists("orderId", $_POST)) {
    updateInvoice($conn);
} else {
    createNewInvoice($conn);
}
$conn->close();
?>
