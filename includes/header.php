<?php
if (!isset($site_root_prefix)) {
    $site_root_prefix = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | ' : ''; ?>RiftMind — AI League of Legends Coaching</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($site_root_prefix); ?>css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <header class="site-header">
            <a href="<?php echo htmlspecialchars($site_root_prefix); ?>index.php" class="logo">RiftMind</a>
            <nav class="main-nav">
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>index.php">Home</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>about.php">About</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>products.php">Services</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>news.php">News</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>contacts.php">Contacts</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>all-company-users.php">All companies’ users</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>secure.php">Secure</a>
            </nav>
        </header>
        <main class="main-content">
