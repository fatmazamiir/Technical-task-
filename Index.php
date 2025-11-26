<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Register for the Event</h1>
    <form action="process.php" method="POST">
        <label for="full_name">Full Name :</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <label for="profession">Profession :</label>
        <input type="text" id="profession" name="profession"><br>

        <label for="address">Address :</label>
        <textarea id="address" name="address"></textarea><br>

        <label for="phone">Phone :</label>
        <input type="tel" id="phone" name="phone" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
