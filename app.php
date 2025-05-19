<?php
$tasksFile = 'tasks.json';
$tasks = file_exists($tasksFile) ? json_decode(file_get_contents($tasksFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $taskId = $_POST['id'] ?? null;

    if ($action === 'add') {
        $newTask = trim($_POST['task']);
        if ($newTask !== '') {
            $tasks[] = ['task' => htmlspecialchars($newTask), 'done' => false];
        }
    } elseif ($action === 'toggle' && isset($tasks[$taskId])) {
        $tasks[$taskId]['done'] = !$tasks[$taskId]['done'];
    } elseif ($action === 'delete' && isset($tasks[$taskId])) {
        array_splice($tasks, $taskId, 1);
    }

    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple To-Do App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-white text-dark min-vh-100 d-flex flex-column">

    <div class="container my-auto" style="max-width: 500px;">
        <h1 class="text-center mb-4 fw-bold">Simple To-Do App</h1>

        <form method="post" class="input-group mb-4">
            <input type="text" name="task" class="form-control border-black" placeholder="Schedule task...">
            <input type="hidden" name="action" value="add">
            <button class="btn btn-black" type="submit">+</button>
        </form>

        <ul class="list-group">
            <?php foreach ($tasks as $index => $task): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <form method="post" class="flex-grow-1 me-2">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?= $index ?>">
                        <button type="submit" class="btn text-start w-100 <?= $task['done'] ? 'text-decoration-line-through text-muted' : '' ?>">
                            <?= $task['task'] ?>
                        </button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $index ?>">
                        <button type="submit" class="btn btn-sm btn-black">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer class="text-center mt-4 py-3 text-muted small">
        Ostad Module 3 Project 1 by Mehedi
    </footer>

    <style>
        body {
            background-color: #fff;
            color: #000;
        }

        .btn-black {
            background-color: #000;
            color: #fff;
            border: 1px solid #000;
        }

        .btn-black:hover {
            background-color: #fff;
            color: #000;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #000;
        }

        .list-group-item {
            background-color: #fff;
            border: 1px solid #000;
        }
    </style>
</body>


</html>
 

