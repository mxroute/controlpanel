<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'DirectAdminAPI.php';
    $username = strip_tags(htmlspecialchars($_POST['username']));
    $password = strip_tags(htmlspecialchars($_POST['password']));

    $directAdmin = new DirectAdminAPI($username, $password, 'arrow.mxrouting.net', 2222);

    if ($directAdmin->authenticate()) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: index.php");
        exit();
    } else {
        $loginFailed = true;
        $errorMessage = $directAdmin->getLastError();
    }    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($loginFailed) && $loginFailed): ?>
    <p style="color: red;">Login failed: <?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
