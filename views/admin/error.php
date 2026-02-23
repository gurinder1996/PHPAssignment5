<?php
// error page
if (!isset($error)) {
    $error = "An error occurred.";
}
include __DIR__ . '/../header.php';
?>

<h2>Error</h2>
<p><?= htmlspecialchars($error) ?></p>
<p><a href="javascript:history.back()">Go Back</a></p>

<?php include __DIR__ . '/../footer.php'; ?>
