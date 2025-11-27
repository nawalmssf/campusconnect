<?php
// test_connection.php
require_once 'db_connect.php';

try {
    $conn = getDBConnection();
    echo "<div style='background: #27ae60; color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px;'>
            <h2>✅ Connection Successful!</h2>
            <p>Connexion à XAMPP MySQL établie avec succès.</p>
          </div>";
} catch (Exception $e) {
    echo "<div style='background: #e74c3c; color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px;'>
            <h2>❌ Connection Failed</h2>
            <p>" . $e->getMessage() . "</p>
          </div>";
}
?>