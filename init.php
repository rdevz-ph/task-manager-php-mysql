<?php
// Redirect to setup if config doesn't exist
if (!file_exists('config.php')) {
    header('Location: setup.php');
    exit;
}
?>