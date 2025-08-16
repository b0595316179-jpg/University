<?php
include '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid task ID");

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
?>