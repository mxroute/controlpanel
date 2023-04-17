<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

require_once 'DirectAdminAPI.php';

$domains = $directAdmin->getDomainList();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Control Panel</title>
</head>
<body>
    <h1>Domain List</h1>
    <ul>
    <?php foreach ($domains as $domain): ?>
    <li><a href="email_accounts.php?domain=<?php echo htmlspecialchars($domain); ?>"><?php echo htmlspecialchars($domain); ?></a></li>
    <?php endforeach; ?>
        <p><a href="logout.php">Logout</a></p>
    </ul>
</body>
</html>
