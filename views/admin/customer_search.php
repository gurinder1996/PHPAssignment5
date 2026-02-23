<?php
// manage customers - search by last name
require __DIR__ . '/../../db/database.php';

$lastName = '';
$customers = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = $_POST['lastName'];

    if ($lastName) {
        $stmt = $db->prepare("SELECT * FROM customers WHERE lastName = :lastName");
        $stmt->execute([':lastName' => $lastName]);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
include __DIR__ . '/../header.php';

?>
<h2 class="mb-3">Customer Search</h2>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h4>Search by Last Name</h4>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="lastName" class="form-control"
                       value="<?= htmlspecialchars($lastName) ?>" required>
            </div>
            <button class="btn btn-primary">Search</button>
            <a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>
        </form>
    </div>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <?php if (count($customers) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['firstName']) ?></td>
                    <td><?= htmlspecialchars($customer['lastName']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= htmlspecialchars($customer['city']) ?></td>
                    <td>
                        <a href="customer_update.php?id=<?= (int)$customer['customerID'] ?>"
                           class="btn btn-sm btn-outline-warning">Select</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">
            No customers found with that last name.
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../footer.php'; ?>
