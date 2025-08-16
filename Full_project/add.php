<?php
include '../config/db.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
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
        .form-container {
            transform: translateY(20px);
            opacity: 0;
        }
        
        .form-enter {
            transform: translateY(0);
            opacity: 1;
        }
        
        .input-field:focus {
            border-color: #d6a99d;
            box-shadow: 0 0 0 3px rgba(214, 169, 157, 0.2);
        }
        
        .btn-primary {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <?php include 'header.php'; ?>
    
    <div class="container mx-auto px-4 py-8 max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Add New Task</h1>
            <p class="text-gray-600">Fill in the details below to create a new task</p>
        </div>
        
        <div class="form-container bg-white rounded-2xl shadow-xl p-6">
            <form method="POST" id="taskForm" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="title">
                        <i class="fas fa-heading mr-2 text-primary"></i>Task Title
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title"
                        placeholder="What needs to be done?" 
                        required 
                        class="input-field w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary transition-all"
                    >
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="description">
                        <i class="fas fa-align-left mr-2 text-primary"></i>Description
                    </label>
                    <textarea 
                        name="description" 
                        id="description"
                        placeholder="Add details (optional)" 
                        class="input-field w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary transition-all min-h-[120px]"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-medium mb-2" for="status">
                        <i class="fas fa-tasks mr-2 text-primary"></i>Status
                    </label>
                    <select 
                        name="status" 
                        id="status"
                        class="input-field w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary transition-all appearance-none bg-white"
                    >
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        onclick="history.back()" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-xl transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 btn-primary bg-gradient-to-r from-primary to-secondary text-white font-medium py-3 px-4 rounded-xl flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-plus-circle"></i> Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate form on load
            gsap.fromTo('.form-container', 
                { y: 20, opacity: 0 },
                { 
                    y: 0, 
                    opacity: 1, 
                    duration: 0.6, 
                    ease: "power2.out"
                }
            );
            
            // Form submission with animation
            const form = document.getElementById('taskForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const title = document.getElementById('title').value.trim();
                if (!title) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Task title is required!',
                        confirmButtonColor: '#d6a99d'
                    });
                    return;
                }
                
                // Animate button press
                const submitBtn = form.querySelector('button[type="submit"]');
                gsap.to(submitBtn, {
                    scale: 0.95,
                    duration: 0.1,
                    onComplete: function() {
                        gsap.to(submitBtn, {
                            scale: 1,
                            duration: 0.1,
                            onComplete: function() {
                                form.submit();
                            }
                        });
                    }
                });
            });
            
            // Input field animations
            const inputs = document.querySelectorAll('.input-field');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    gsap.to(this, {
                        scale: 1.02,
                        duration: 0.2,
                        ease: "power2.out"
                    });
                });
                
                input.addEventListener('blur', function() {
                    gsap.to(this, {
                        scale: 1,
                        duration: 0.2,
                        ease: "power2.out"
                    });
                });
            });
        });
    </script>
</body>
</html>