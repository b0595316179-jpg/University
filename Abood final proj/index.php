<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'header.php'; ?>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">My Tasks</h1>
        <a href="add.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Task</a>
        <ul class="space-y-2">
            <?php
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                $statusClass = $row['status'] === 'completed' ? 'line-through text-gray-500' : '';
            ?>
                <li class="bg-white p-4 rounded shadow flex justify-between items-center">
                    <div>
                        <span class="<?= $statusClass ?>"><?= htmlspecialchars($row['title']) ?></span>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($row['description']) ?></p>
                    </div>
                    <div>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500 mr-2">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>