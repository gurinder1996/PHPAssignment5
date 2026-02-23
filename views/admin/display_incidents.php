<?php
// display incidents - unassigned and assigned views
require __DIR__ . '/../../db/database.php';

$view = $_GET['view'] ?? 'unassigned';

if ($view === 'assigned') {
    // get all assigned incidents
    $sql = "SELECT i.incidentID, i.dateOpened, i.title, i.description, i.dateClosed,
                   CONCAT(c.firstName, ' ', c.lastName) AS customerName,
                   p.name AS productName,
                   CONCAT(t.firstName, ' ', t.lastName) AS techName
            FROM incidents i
            JOIN customers c ON i.customerID = c.customerID
            JOIN products p ON i.productCode = p.productCode
            JOIN technicians t ON i.techID = t.techID
            ORDER BY i.dateOpened DESC";
    $incidents = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} else {
    // get unassigned incidents
    $sql = "SELECT i.incidentID, i.dateOpened, i.title, i.description,
                   CONCAT(c.firstName, ' ', c.lastName) AS customerName,
                   p.name AS productName
            FROM incidents i
            JOIN customers c ON i.customerID = c.customerID
            JOIN products p ON i.productCode = p.productCode
            WHERE i.techID IS NULL
            ORDER BY i.dateOpened DESC";
    $incidents = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

include __DIR__ . '/../header.php';
?>

<?php if ($view === 'assigned'): ?>
    <h2 class="mb-3">Assigned Incidents</h2>
    <p><a href="display_incidents.php?view=unassigned">View Unassigned Incidents</a></p>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Incident ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Date Opened</th>
                <th>Title</th>
                <th>Description</th>
                <th>Technician</th>
                <th>Date Closed</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidents as $row): ?>
            <tr>
                <td><?= (int)$row['incidentID'] ?></td>
                <td><?= htmlspecialchars($row['customerName']) ?></td>
                <td><?= htmlspecialchars($row['productName']) ?></td>
                <td><?= date('m-d-Y', strtotime($row['dateOpened'])) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['techName']) ?></td>
                <td>
                    <?php if ($row['dateClosed']): ?>
                        <?= date('m-d-Y', strtotime($row['dateClosed'])) ?>
                    <?php else: ?>
                        OPEN
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <h2 class="mb-3">Unassigned Incidents</h2>
    <p><a href="display_incidents.php?view=assigned">View Assigned Incidents</a></p>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Incident ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Date Opened</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidents as $row): ?>
            <tr>
                <td><?= (int)$row['incidentID'] ?></td>
                <td><?= htmlspecialchars($row['customerName']) ?></td>
                <td><?= htmlspecialchars($row['productName']) ?></td>
                <td><?= date('m-d-Y', strtotime($row['dateOpened'])) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>

<?php include __DIR__ . '/../footer.php'; ?>
