<?php

session_start();

require_once "config/database.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === "" || $password === "") {

        $error = "Please enter username and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, username, password, role
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Simple plain-text password comparison
            if ($password === $user['password']) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Incorrect password.";

            }

        } else {

            $error = "Username not found.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Inventory Management System</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="login-body">

<div class="login-box">

    <h1>Inventory Management System</h1>

    <h2>Login</h2>

    <?php if ($error): ?>

        <div class="alert error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            placeholder="Enter username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter password"
            required
        >

        <button type="submit" class="btn">
            Login
        </button>

    </form>

</div>

</body>
</html>