<?php
include 'config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid task ID");

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) die("Task not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $status = $_POST['status'];

    if (!empty($title)) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $desc, $status, $id]);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto p-4 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Edit Task</h1>
        <form method="POST" class="space-y-4">
            <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required class="w-full p-2 border rounded">
            <textarea name="description" class="w-full p-2 border rounded"><?= htmlspecialchars($task['description']) ?></textarea>
            <select name="status" class="w-full p-2 border rounded">
                <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
        </form>
    </div>
</body>
</html>