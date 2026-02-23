<?php
// delete technician and redirect back
require __DIR__ . '/../../db/database.php';

$id = filter_input(INPUT_POST, 'techID', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: manage_technicians.php");
    exit;
}

$stmt = $db->prepare("DELETE FROM technicians WHERE techID = :id");
$stmt->execute([':id' => $id]);

header("Location: manage_technicians.php");
exit;
