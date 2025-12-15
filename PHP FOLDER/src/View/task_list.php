<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Лист Задач</title>
</head>

<body>
    <div>
        <a href="?route=task/switch-mode&mode=mysql"<?= ($_SESSION['repository_mode'] ?? 'mysql') === 'mysql' ? 'active' : '' ?>>
            MySQL режим
        </a>
        <a href="?route=task/switch-mode&mode=file"<?= ($_SESSION['repository_mode'] ?? 'mysql') === 'file' ? 'active' : '' ?>>
            File режим
        </a>
    </div>

    <div >
        <h1>Список задач (<?= ($_SESSION['repository_mode'] ?? 'mysql') === 'mysql' ? 'MySQL' : 'File' ?>)</h1>
        <a href="?route=task/add">+ Добавить задачу</a>
    </div>
    
    <?php if (empty($tasks)): ?>
        <div>
            <p>Задачи отсутствуют. Добавьте первую задачу!</p>
        </div>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <button <?= $task->isCompleted() ? 'completed' : '' ?> 
                            onclick="location.href='?route=task/toggle&id=<?= $task->getId() ?>'">
                        <?= $task->isCompleted() ? "✓" : "X" ?>
                    </button>
                    
                    <div >
                        <?= htmlspecialchars($task->getTitle()) ?>
                    </div>
                    
                    <div >
                        <span >
                            <?= $task->isCompleted() ? "Выполнено" : "Не выполнено" ?>
                        </span>
                        <button 
                                onclick="if(confirm('Удалить задачу?')) location.href='?route=task/delete&id=<?= $task->getId() ?>'">
                            Удалить
                        </button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>