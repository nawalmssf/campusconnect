<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli("localhost", "root", "", "campusconnect");
    echo "MYSQL CONNECTION OK";
} catch (Exception $e) {
    echo "MYSQL ERROR: " . $e->getMessage();
}
