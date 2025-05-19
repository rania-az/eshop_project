<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_panel.php");
            exit;
        } else {
            $error = "Λάθος κωδικός!";
        }
    } else {
        $error = "Δεν υπάρχει αυτός ο χρήστης!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Σύνδεση Admin</title>
</head>
<body>
    <h2>Σύνδεση Διαχειριστή</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Όνομα χρήστη:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Κωδικός:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Σύνδεση">
    </form>
</body>
</html>
