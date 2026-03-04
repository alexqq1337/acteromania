<?php
/**
 * ActeRomânia CMS - Logout
 */
require_once '../config.php';

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header('Location: ' . ADMIN_URL . '/index.php');
exit;
