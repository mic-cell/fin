<?php
session_start();
require 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == USERNAME && $password == PASSWORD) {
        $_SESSION['loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
