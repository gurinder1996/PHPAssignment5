<?php
// manage products - list all products
require __DIR__ . '/../../db/database.php';
$products = $db->query("SELECT * FROM products ORDER BY productCode")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Product List</h2>
    <a href="add_product.php" class="btn btn-primary">Add Product</a>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Product Code</th>
            <th>Name</th>
            <th>Version</th>
            <th>Release Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['productCode']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['version']) ?></td>
                <td><?= date('Y-m-d', strtotime($product['releaseDate'])) ?></td>
                <td>
                    <form class="d-inline"
                          action="delete_product.php"
                          method="post"
                          onsubmit="return confirm('Delete this product?');">
                        <input type="hidden" name="productCode"
                               value="<?= htmlspecialchars($product['productCode']) ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>

<?php include __DIR__ . '/../footer.php'; ?>
