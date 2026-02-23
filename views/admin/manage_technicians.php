<?php
// manage technicians - list all technicians
require __DIR__ . '/../../db/database.php';
$technicians = $db->query("SELECT * FROM technicians ORDER BY lastName")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Technician List</h2>
    <a href="add_technician.php" class="btn btn-primary">Add Technician</a>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($technicians as $tech): ?>
        <tr>
            <td><?= htmlspecialchars($tech['techID']) ?></td>
            <td><?= htmlspecialchars($tech['firstName']) ?></td>
            <td><?= htmlspecialchars($tech['lastName']) ?></td>
            <td><?= htmlspecialchars($tech['email']) ?></td>
            <td><?= htmlspecialchars($tech['phone']) ?></td>
            <td>
                <form class="d-inline"
                      action="delete_technician.php"
                      method="post"
                      onsubmit="return confirm('Delete this technician?');">
                    <input type="hidden" name="techID" value="<?= (int)$tech['techID'] ?>">
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
