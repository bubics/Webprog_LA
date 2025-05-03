<?php
session_start();
require_once 'config.php';

$page = $_GET['page'] ?? 'home';


$allowed_pages = array_keys($config['menu']);
$allowed_pages[] = 'login';
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}


include 'templates/header.php';


include "controllers/$page.php";


include 'templates/footer.php';
