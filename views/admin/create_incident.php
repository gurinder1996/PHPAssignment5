<?php
// create incident for a customer
require __DIR__ . '/../../db/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: get_customer.php");
    exit;
}

// get customer info
$stmt = $db->prepare("SELECT * FROM customers WHERE customerID = :id");
$stmt->execute([':id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    header("Location: get_customer.php");
    exit;
}

// get products that this customer has registered
$stmt = $db->prepare("SELECT p.productCode, p.name
                       FROM products p
                       JOIN registrations r ON p.productCode = r.productCode
                       WHERE r.customerID = :id");
$stmt->execute([':id' => $id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productCode = $_POST['productCode'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if ($productCode && $title) {
        $sql = "INSERT INTO incidents (customerID, productCode, title, description)
                VALUES (:customerID, :productCode, :title, :description)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':customerID' => $id,
            ':productCode' => $productCode,
            ':title' => $title,
            ':description' => $description
        ]);

        $message = "Incident has been added to the database.";
    } else {
        $message = "Please select a product and enter a title.";
    }
}

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Create Incident</h2>

<div class="card shadow mb-3">
    <div class="card-header bg-info text-white">
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
        <h4>Incident Details</h4>
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

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <button class="btn btn-success">Create Incident</button>
            <a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
