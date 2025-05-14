<?php
session_start();
require 'database.php';
require_once 'classes/User.php';

$user = unserialize($_SESSION['user'] ?? null);
if (!$user || $user->getRole() !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<h2>Selamat datang, Admin <?= $user->getName(); ?></h2>
<ul>
    <li><a href="#">Daftar Unit AC</a></li>
    <li><a href="#">Tambah User</a></li>
    <li><a href="#">Log Perawatan</a></li>
</ul>
