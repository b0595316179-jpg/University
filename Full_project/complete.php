<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Update task status to completed
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
    $stmt->execute([$id]);
    
    // Redirect back to index with success message
    header('Location: index.php?completed=1');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>