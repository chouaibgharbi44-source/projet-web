<?php
echo "<h2>File Check</h2>";

$files = [
    'js/gestion.js' => file_exists(__DIR__ . '/js/gestion.js'),
    'css/style-gestion.css' => file_exists(__DIR__ . '/css/style-gestion.css'),
    '../config.php' => file_exists(__DIR__ . '/../config.php'),
    '../controllers/api/users.php' => file_exists(__DIR__ . '/../controllers/api_users.php'),
];

foreach ($files as $file => $exists) {
    $status = $exists ? '✅ EXISTS' : '❌ NOT FOUND';
    echo "<p>$file: $status</p>";
}

echo "<hr><h3>Current directory:</h3>";
echo "<p>" . __DIR__ . "</p>";
?>