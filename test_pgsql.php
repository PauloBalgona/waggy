<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;dbname=waggydb;port=5432', 'postgres', 'your_password');
    echo "PostgreSQL connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>