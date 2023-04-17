<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header("Location: login.php");
    exit();
}

require_once 'DirectAdminAPI.php';

$domain = $_GET['domain'];
$domain = strip_tags(htmlspecialchars($domain));
$emailaccounts = $directAdmin->getEmailAccounts($domain);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Control Panel</title>
</head>
<body>
    <h1>Email List</h1>
    <ul>
    <?php foreach ($emailaccounts as $emailaccount): ?>
    <li><a href="edit_email.php?<?php echo "domain=" . $domain . "&acct=" . $emailaccount; ?>"><?php echo htmlspecialchars($emailaccount); ?></a></li>
    <?php endforeach; ?>
        <p><a href="index.php">Back to index</a></p>
        <p><a href="logout.php">Logout</a></p>
    </ul>
</body>
</html>
