<?php
// assign incident - select an unassigned incident
session_start();
require __DIR__ . '/../../db/database.php';

// get unassigned incidents with customer info
$sql = "SELECT i.incidentID, i.dateOpened, i.title,
               CONCAT(c.firstName, ' ', c.lastName) AS customerName
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        WHERE i.techID IS NULL
        ORDER BY i.dateOpened";
$incidents = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Select Incident to Assign</h2>

<?php if (count($incidents) > 0): ?>
<table class="table table-striped table-bordered">
	<thead class="table-dark">
		<tr>
			<th>Incident ID</th>
			<th>Customer</th>
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
			<td><?= date('m-d-Y', strtotime($row['dateOpened'])) ?></td>
			<td><?= htmlspecialchars($row['title']) ?></td>
			<td>
				<a href="select_technician.php?incidentID=<?= (int)$row['incidentID'] ?>"
				   class="btn btn-sm btn-outline-primary">Select</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
    <div class="alert alert-info">There are no unassigned incidents.</div>
<?php endif; ?>

<a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>

<?php include __DIR__ . '/../footer.php'; ?>
