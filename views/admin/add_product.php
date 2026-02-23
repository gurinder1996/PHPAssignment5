<?php
// add product form
require __DIR__ . '/../../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['productCode'];
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    // check all fields
    if ($code && $name && $version && $releaseDate) {
        $sql = "INSERT INTO products (productCode, name, version, releaseDate)
                VALUES (:code, :name, :version, :releaseDate)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':code' => $code,
            ':name' => $name,
            ':version' => $version,
            ':releaseDate' => $releaseDate
        ]);

        header("Location: project_manager.php");
        exit;
    } else {
        $error = "Please fill in all fields.";
        include 'error.php';
        exit;
    }
}
include __DIR__ . '/../header.php';

?>
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4>Add Product</h4>
    </div>

    <div class="card-body">
        <form method="post">

            <div class="mb-3">
                <label class="form-label">Product Code</label>
                <input type="text" name="productCode" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Version</label>
                <input type="text" name="version" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Release Date</label>
                <input type="date" name="releaseDate" class="form-control" required>
            </div>

            <button class="btn btn-success">Add Product</button>
            <a href="project_manager.php" class="btn btn-secondary">View Product List</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
