<?php
session_start();
require 'database.php';
require_once 'classes/User.php';

$user = unserialize($_SESSION['user'] ?? null);
if (!$user || $user->getRole() !== 'petugas') {
    header('Location: index.php');
    exit;
}
?>
<h2>Selamat datang, Petugas <?= $user->getName(); ?></h2>
<ul>
    <li><a href="#">Lihat Unit AC</a></li>
    <li><a href="#">Input Log Perawatan</a></li>
</ul>
