<?php include '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../check.png">
    <title>Modern Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#d6a99d',
                        secondary: '#b48876',
                        dark: '#1e293b',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar{
            background-color: #fff;
            width: 9px;
        }
        ::-webkit-scrollbar-button{
            background-color: #fff;
        }
        ::-webkit-scrollbar-thumb{
            background-color: #b48876;
        }
        ::selection{
            background-color: #d6a99d;
            color: #f8fafc;
        }
        .task-item {
            transition: all 0.3s ease;
            transform: translateY(20px);
            opacity: 0;
        }
        
        .task-enter {
            transform: translateY(0);
            opacity: 1;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #fef7f5 0%, #f8eee9 100%);
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <?php include 'header.php'; ?>
    
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-3">Task Manager</h1>
            <p class="text-gray-600 max-w-md mx-auto">Organize your work and life with this simple yet powerful task manager</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 hover-lift">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-800">My Tasks</h2>
                <a href="add.php" class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full font-medium flex items-center gap-2 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-plus"></i>
                    Add New Task
                </a>
            </div>
        </div>

        <div class="task-list space-y-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($tasks)): ?>
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">No tasks yet</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first task</p>
                    <a href="add.php" class="bg-primary text-white px-6 py-3 rounded-full font-medium inline-flex items-center gap-2 hover:bg-secondary transition-colors">
                        <i class="fas fa-plus"></i>
                        Create Task
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($tasks as $index => $row): 
                    $statusClass = $row['status'] === 'completed' ? 'line-through text-gray-500' : 'text-gray-800';
                    $statusBgClass = $row['status'] === 'completed' ? 'status-completed' : 'status-pending';
                ?>
                    <div class="task-item bg-white rounded-2xl shadow-lg p-6 hover-lift <?= $statusBgClass ?>" 
                         data-task-id="<?= $row['id'] ?>"
                         data-index="<?= $index ?>">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold <?= $statusClass ?> mb-2">
                                    <?= htmlspecialchars($row['title']) ?>
                                </h3>
                                <p class="text-gray-600 mb-4"><?= htmlspecialchars($row['description']) ?></p>
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>Created: <?= date('M j, Y', strtotime($row['created_at'])) ?></span>
                                    <?php if ($row['status'] === 'completed'): ?>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <span>Completed</span>
                                    <?php else: ?>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-clock text-yellow-500"></i>
                                        <span>Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <?php if ($row['status'] !== 'completed'): ?>
                                    <button class="complete-btn bg-green-500 hover:bg-green-600 text-white p-3 rounded-full transition-colors"
                                            data-id="<?= $row['id'] ?>">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <a href="edit.php?id=<?= $row['id'] ?>" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button class="delete-btn bg-red-500 hover:bg-red-600 text-white p-3 rounded-full transition-colors"
                                        data-id="<?= $row['id'] ?>"
                                        data-title="<?= htmlspecialchars($row['title']) ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate tasks on load
            gsap.utils.toArray('.task-item').forEach((element, i) => {
                gsap.fromTo(element, 
                    { y: 20, opacity: 0 },
                    { 
                        y: 0, 
                        opacity: 1, 
                        duration: 0.5, 
                        delay: i * 0.1,
                        ease: "power2.out"
                    }
                );
            });
            
            // Delete task with SweetAlert2
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-id');
                    const taskTitle = this.getAttribute('data-title');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete "${taskTitle}". This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d6a99d',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `delete.php?id=${taskId}`;
                        }
                    });
                });
            });
            
            // Complete task with animation
            document.querySelectorAll('.complete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-id');
                    const taskElement = this.closest('.task-item');
                    
                    // Animate the task
                    gsap.to(taskElement, {
                        scale: 0.95,
                        duration: 0.2,
                        onComplete: function() {
                            // Redirect to complete script
                            window.location.href = `complete.php?id=${taskId}`;
                        }
                    });
                });
            });
            
            // Add hover effect to task items
            document.querySelectorAll('.task-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        y: -5,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });
                
                item.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        y: 0,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                });
            });
        });
    </script>
</body>
</html>