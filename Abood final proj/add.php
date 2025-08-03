<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $status = $_POST['status'] ?? 'pending';

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        $stmt->execute([$title, $desc, $status]);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto p-4 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Add New Task</h1>
        <form method="POST" class="space-y-4">
            <input type="text" name="title" placeholder="Task Title" required class="w-full p-2 border rounded">
            <textarea name="description" placeholder="Description" class="w-full p-2 border rounded"></textarea>
            <select name="status" class="w-full p-2 border rounded">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Task</button>
        </form>
    </div>
</body>
</html>