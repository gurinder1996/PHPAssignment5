<?php
// update incident - technician can update description and close
session_start();
require __DIR__ . '/../../db/database.php';

// check if logged in
if (!isset($_SESSION['tech'])) {
    header("Location: tech_login.php");
    exit;
}

// handle logout
if (isset($_GET['action']) && $_GET['action']==='logout') {
    unset($_SESSION['tech']);
    session_destroy();
    header("Location: tech_login.php");
    exit;
}

$tech = $_SESSION['tech'];
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: incident_list.php");
    exit;
}

// get incident data
$stmt = $db->prepare("SELECT i.*, p.name AS productName,
                             CONCAT(c.firstName, ' ', c.lastName) AS customerName
                      FROM incidents i
                      JOIN products p ON i.productCode = p.productCode
                      JOIN customers c ON i.customerID = c.customerID
                      WHERE i.incidentID = :id");
$stmt->execute([':id' => $id]);
$incident = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$incident) {
    header("Location: incident_list.php");
    exit;
}

$message = '';

// handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $dateClosed = $_POST['dateClosed'];

    // update the incident
    if ($dateClosed) {
        $stmt = $db->prepare("UPDATE incidents SET description = :desc, dateClosed = :dateClosed
                              WHERE incidentID = :id");
        $stmt->execute([
            ':desc' => $description,
            ':dateClosed' => $dateClosed,
            ':id' => $id
        ]);
    } else {
        $stmt = $db->prepare("UPDATE incidents SET description = :desc WHERE incidentID = :id");
        $stmt->execute([':desc' => $description, ':id' => $id]);
    }

    $message = "Incident " . $id . " has been updated.";
}

include __DIR__ . '/../header.php';
?>

<h2 class="mb-3">Update Incident</h2>

<div class="mb-3">
    <p>Logged in as: <?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?></p>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <p><a href="incident_list.php">Select Another Incident</a></p>
<?php else: ?>

<div class="card shadow mb-3">
    <div class="card-header bg-info text-white">
        <h4>Incident Info</h4>
    </div>
    <div class="card-body">
        <p><strong>Incident ID:</strong> <?= (int)$incident['incidentID'] ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($incident['customerName']) ?></p>
        <p><strong>Product:</strong> <?= htmlspecialchars($incident['productName']) ?></p>
        <p><strong>Date Opened:</strong> <?= date('m-d-Y', strtotime($incident['dateOpened'])) ?></p>
        <p><strong>Title:</strong> <?= htmlspecialchars($incident['title']) ?></p>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-warning">
        <h4>Update Details</h4>
    </div>
    <div class="card-body">
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($incident['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Date Closed (leave blank if not resolved)</label>
                <input type="date" name="dateClosed" class="form-control">
            </div>

            <button class="btn btn-success">Update Incident</button>
            <a href="incident_list.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php endif; ?>

<div class="mt-3">
    <a href="update_incident.php?action=logout" class="btn btn-danger">Logout</a>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
