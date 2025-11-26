<?php
$db = new PDO('mysql:host=localhost;dbname=eventdb', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$id || !$token) {
    die("Invalid link.");
}

// Check token and expiry
$stmt = $db->prepare("SELECT * FROM registrations WHERE id = ? AND confirm_token = ? AND token_expires_at > NOW() AND status = 'pending'");

$stmt->execute([$id, $token]);
$reg = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reg) {
    die("Invalid or expired token.");
}

// Update status
$db->prepare("UPDATE registrations SET status = 'confirmed', confirmed_at = datetime('now') WHERE id = ?")->execute([$id]);

echo "<h1>Thank You!</h1><p>Your registration is confirmed.</p>";
?>
