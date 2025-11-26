<?php
// Database connection 
$db = new PDO('mysql:host=localhost;dbname=eventdb', 'root', '');  
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Validate required fields
if (empty($_POST['full_name']) || empty($_POST['phone']) || empty($_POST['email'])) {
    die("All required fields must be filled.");
}

// token and expiry 
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+7 days'));  
// Insert into DB 
$stmt = $db->prepare("INSERT INTO registrations (full_name, profession, address, phone, email, confirm_token, token_expires_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $_POST['full_name'],
    $_POST['profession'] ?? null,
    $_POST['address'] ?? null,
    $_POST['phone'],
    $_POST['email'],
    $token,
    $expires
]);
$id = $db->lastInsertId();

// Send email 
$confirmLink = "http://localhost/event-registration/confirm.php?id=$id&token=$token";
$subject = "Confirm Your Event Registration";
$message = "Click here to confirm: $confirmLink";

if (mail($_POST['email'], $subject, $message)) {
    echo "Registration submitted. Check your email for confirmation.";
} else {
    echo "Email could not be sent. Use this link to confirm: $confirmLink";
}
?>
