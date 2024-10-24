<?php
session_start();
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Example query to check user credentials
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role']; // Assume 'role' is a column in your users table

        if ($user['role'] === 'admin') {
            header("Location: index.php");
        } else if ($user['role'] === 'employee') {
            header("Location: employee.php");
        }
        exit;
    } else {
        $error = "Invalid login credentials!";
    }

    $conn->close();
}
?>


<?php if (isset($_SESSION['login_error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['login_error']; ?>
    </div>
    <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Into management system</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="main-header login-header">
        <div class="brand-title" >
            <h1>IndianOil <span style="color: red;">Sky</span><span style="color: rgb(248, 221, 15);">tanking</span></h1>
        </div>
        <i class="fas fa-bars" id="menu-toggle"></i>
    </header>
    <main class="login-main">
        <section class="login-container">
            <h2>Login</h2>
            <form action="login.php" method="post" class="login-form">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">Login</button>
                
            </form>
            <?php if (isset($error))
                echo "<p>$error</p>"; ?>
        </section>
    </main>
    <footer class="footer">
        <p>Powered by <a href="https://technicalraven.netlify.app" target="_blank">Technical Raven</a> © 2024</p>
    </footer>



    
</body>
</html>