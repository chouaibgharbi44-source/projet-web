<?php
// db_test.php - Use this file to test your database connection

// Ensure the path to your config file is correct
require_once "../config.php";

echo "<!DOCTYPE html><html><head><title>DB Test</title><style>body {font-family: sans-serif; background-color: #1a1a1a; color: #e0e0e0; padding: 20px;} .success {color: #50fa7b; border: 1px solid #50fa7b; padding: 10px; border-radius: 5px;} .error {color: #ff5555; border: 1px solid #ff5555; padding: 10px; border-radius: 5px;}</style></head><body>";

// Check if the global $pdo variable was created successfully in config.php
if (isset($pdo) && $pdo instanceof PDO) {
    try {
        // Attempt a simple query to ensure the connection is truly open
        $stmt = $pdo->query('SELECT 1');
        if ($stmt) {
            echo "<div class='success'><h1>✅ SUCCESS!</h1>";
            echo "<p>Database connection established successfully.</p>";
            echo "<p>The problem is NOT your database connection file.</p></div>";
        } else {
             // This case is unlikely due to the ERRMODE_EXCEPTION setting, but added for safety
             echo "<div class='error'><h1>❌ ERROR!</h1>";
             echo "<p>Connection seems open, but a simple query failed. This points to a credentials or permission issue.</p></div>";
        }
    } catch (\PDOException $e) {
        // This will catch the error if the credentials were correct but the database is missing/corrupt
        echo "<div class='error'><h1>❌ CONNECTION FAILURE! (PDO Exception)</h1>";
        echo "<p><strong>Error Details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Check your username, password, and database name in <code>config.php</code>.</p></div>";
    }
} else {
    echo "<div class='error'><h1>❌ FATAL ERROR!</h1>";
    echo "<p>The PDO object was not created. Check your <code>require_once</code> path and <code>config.php</code> for syntax errors.</p></div>";
}

echo "</body></html>";

?>