<?php
// select technician and assign the incident
session_start();
require __DIR__ . '/../../db/database.php';

$incidentID = filter_input(INPUT_GET, 'incidentID', FILTER_VALIDATE_INT);

// save incident id in session
if ($incidentID) {
    $_SESSION['incidentID'] = $incidentID;
} else {
    $incidentID = $_SESSION['incidentID'] ?? 0;
}

if (!$incidentID) {
    header("Location: assign_incident.php");
    exit;
}

$message = '';

// handle the assign
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $techID = filter_input(INPUT_POST, 'techID', FILTER_VALIDATE_INT);
    $_SESSION['techID'] = $techID;

    if ($techID && $incidentID) {
        $stmt = $db->prepare("UPDATE incidents SET techID = :techID WHERE incidentID = :incidentID");
        $stmt->execute([
            ':techID' => $techID,
            ':incidentID' => $incidentID
        ]);
        $message = "Incident " . $incidentID . " has been assigned to technician " . $techID . ".";
    }
}

// get technicians with open incident count
$sql = "SELECT t.techID, t.firstName, t.lastName,
               (SELECT COUNT(*) FROM incidents i
                WHERE i.techID = t.techID AND i.dateClosed IS NULL) AS openCount
        FROM technicians t
        ORDER BY t.lastName";
$technicians = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Select Technician</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <p><a href="assign_incident.php">Select Another Incident</a></p>
<?php else: ?>
    <p>Assigning incident #<?= (int)$incidentID ?></p>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Open Incidents</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($technicians as $tech): ?>
            <tr>
                <td><?= (int)$tech['techID'] ?></td>
                <td><?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?></td>
                <td><?= (int)$tech['openCount'] ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="techID" value="<?= (int)$tech['techID'] ?>">
                        <button class="btn btn-sm btn-outline-success" type="submit">Select</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="/SportsPro/index.php" class="btn btn-secondary">Home</a>

<?php include __DIR__ . '/../footer.php'; ?>
