<?php
$db = new PDO('mysql:host=localhost;dbname=eventdb', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_GET['admin']) || $_GET['admin'] !== 'secret') {
    die("Access denied.");
}

// show all registrations
$regs = $db->query("SELECT * FROM registrations ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// CSV Export
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="registrations.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($regs[0]));
    foreach ($regs as $reg) {
        fputcsv($output, $reg);
    }
    fclose($output);
    exit;
}

// Pick random winner 
if (isset($_GET['pick_winner'])) {
    $confirmed = $db->query("SELECT * FROM registrations WHERE status = 'confirmed'")->fetchAll(PDO::FETCH_ASSOC);
    if ($confirmed) {
        $winner = $confirmed[array_rand($confirmed)];
        echo "<h2>Random Winner: {$winner['full_name']} ({$winner['email']})</h2>";
    } else {
        echo "<h2>No confirmed registrations yet.</h2>";
    }
}
?>

<h1>Admin - Registrations</h1>
<table border="1">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th><th>Confirmed</th>
    </tr>
    <?php foreach ($regs as $reg): ?>
        <tr style="background-color: <?= $reg['status'] === 'confirmed' ? 'lightgreen' : 'lightyellow'; ?>">
            <td><?= $reg['id'] ?></td>
            <td><?= htmlspecialchars($reg['full_name']) ?></td>
            <td><?= htmlspecialchars($reg['email']) ?></td>
            <td><?= $reg['status'] ?></td>
            <td><?= $reg['created_at'] ?></td>
            <td><?= $reg['confirmed_at'] ?? 'N/A' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="?admin=secret&export=1">Export CSV</a>
<a href="?admin=secret&pick_winner=1">Pick Random Winner</a>
