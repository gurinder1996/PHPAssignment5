<?php
// customer login - enter email to log in
require __DIR__ . '/../../db/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if ($email) {
        $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // found customer, go to register product
            header("Location: register_product.php?id=" . $customer['customerID']);
            exit;
        } else {
            $error = "No customer found with that email address.";
        }
    }
}
include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Customer Login</h2>

<div class="card shadow">
    <div class="card-header bg-success text-white">
        <h4>Login</h4>
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

            <button class="btn btn-primary">Login</button>
            <a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
