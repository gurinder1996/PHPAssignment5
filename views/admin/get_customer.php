<?php
// get customer by email - first step for creating incident
require __DIR__ . '/../../db/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if ($email) {
        $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // found customer, go to create incident page
            header("Location: create_incident.php?id=" . $customer['customerID']);
            exit;
        } else {
            $error = "No customer found with that email.";
        }
    }
}
include __DIR__ . '/../header.php';

?>
<h2 class="mb-3">Get Customer</h2>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4>Enter Customer Email</h4>
    </div>
    <div class="card-body">

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button class="btn btn-primary">Get Customer</button>
            <a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
