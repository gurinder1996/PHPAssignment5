<?php
// list open incidents for logged in technician
session_start();
require __DIR__ . '/../../db/database.php';

// check if logged in
if (!isset($_SESSION['tech'])) {
    header("Location: tech_login.php");
    exit;
}

// handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['tech']);
    session_destroy();
    header("Location: tech_login.php");
    exit;
}

$tech = $_SESSION['tech'];
$techID = (int)$tech['techID'];

// get open incidents assigned to this technician
$stmt = $db->prepare("SELECT i.incidentID, i.dateOpened, i.title, i.description,
                             p.name AS productName,
                             CONCAT(c.firstName,' ',c.lastName) AS customerName
                      FROM incidents i
                      JOIN products p ON i.productCode = p.productCode
                      JOIN customers c ON i.customerID = c.customerID
                      WHERE i.techID = :techID AND i.dateClosed IS NULL
                      ORDER BY i.dateOpened");
$stmt->execute([':techID' => $techID]);
$incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Your Open Incidents</h2>

<div class="mb-3">
    <p>Logged in as: <?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?>
       (<?= htmlspecialchars($tech['email']) ?>)</p>
</div>

<?php if (count($incidents) > 0): ?>
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Incident ID</th>
            <th>Customer</th>
            <th>Product</th>
			<th>Date Opened</th>
            <th>Title</th>
            <th>Action</th>
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
            <td>
                <a href="update_incident.php?id=<?= (int)$row['incidentID'] ?>"
                   class="btn btn-sm btn-outline-primary">Select</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <div class="alert alert-info">
        There are no open incidents assigned to you.
    </div>
    <p><a href="incident_list.php">Refresh List of Incidents</a></p>
<?php endif; ?>

<a href="incident_list.php?action=logout" class="btn btn-danger">Logout</a>

<?php include __DIR__ . '/../footer.php'; ?>
