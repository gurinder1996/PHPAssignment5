<?php
// delete product and redirect back
require __DIR__ . '/../../db/database.php';

$code = $_POST['productCode'];

if (!$code) {
    header("Location: project_manager.php");
    exit;
}

$stmt = $db->prepare("DELETE FROM products WHERE productCode = :code");
$stmt->execute([':code' => $code]);

header("Location: project_manager.php");
exit;
