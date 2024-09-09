<?php
// Include the WordPress configuration file from the same directory
require_once('wp-config.php'); 

try {
    // Connect to the database using credentials from wp-config.php
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to select all MyISAM tables
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND engine = 'MyISAM'");

    // Iterate over the tables and convert each to InnoDB
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tableName = $row['table_name'];
        $pdo->exec("ALTER TABLE `$tableName` ENGINE=InnoDB");
        echo "Converted table $tableName to InnoDB.\n";
    }

    echo "All MyISAM tables have been converted to InnoDB.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
