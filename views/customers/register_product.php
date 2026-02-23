<?php
// register a product for a customer
require __DIR__ . '/../../db/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: customer_login.php");
    exit;
}

// get customer info
$stmt = $db->prepare("SELECT * FROM customers WHERE customerID = :id");
$stmt->execute([':id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    header("Location: customer_login.php");
    exit;
}

// get all products for dropdown
$products = $db->query("SELECT productCode, name FROM products ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productCode=$_POST['productCode'];

    if ($productCode) {
        // check if already registered
        $check = $db->prepare("SELECT * FROM registrations WHERE customerID = :id AND productCode = :code");
        $check->execute([':id' => $id, ':code' => $productCode]);

        if ($check->fetch()) {
            $message = "You have already registered product " . $productCode . ".";
        } else {
            $sql = "INSERT INTO registrations (customerID, productCode)
                    VALUES (:customerID, :productCode)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':customerID' => $id,
                ':productCode' => $productCode
            ]);

            $message = "Product " . $productCode . " has been registered successfully.";
        }
    } else {
        $message = "Please select a product.";
    }
}

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Register Product</h2>

<div class="card shadow mb-3">
    <div class="card-header bg-success text-white">
        <h4>Customer Info</h4>
    </div>
    <div class="card-body">
        <p><strong>Name:</strong> <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4>Register a Product</h4>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Product</label>
                <select name="productCode" class="form-control" required>
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= htmlspecialchars($product['productCode']) ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-success">Register Product</button>
            <a href="customer_login.php" class="btn btn-secondary">Back to Login</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
