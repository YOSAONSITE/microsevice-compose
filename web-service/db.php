<?php
$host = getenv('DB_HOST') ?: 'mariadb-yosa';
$user = getenv('DB_USER') ?: 'yosa_user';
$pass = getenv('DB_PASSWORD') ?: 'yosa_password';
$db   = getenv('DB_NAME') ?: 'db_yosa';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
