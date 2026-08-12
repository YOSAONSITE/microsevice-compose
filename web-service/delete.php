<?php
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $query = "DELETE FROM hardware WHERE id = $id";
    mysqli_query($conn, $query);
}

header('Location: index.php');
exit;
?>
