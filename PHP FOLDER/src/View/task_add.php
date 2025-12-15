<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Добавить задачу</title>
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

    <h1>Добавить новую задачу (<?= ($_SESSION['repository_mode'] ?? 'mysql') === 'mysql' ? 'MySQL' : 'File' ?>)</h1>
    
    <form method="POST" action="?route=task/add">
        <div>
            <label for="title">Название задачи:</label>
            <input type="text" id="title" name="title" required 
                   placeholder="Введите название задачи..." 
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty(trim($_POST['title'] ?? ''))): ?>
                <div>Пожалуйста, введите название задачи</div>
            <?php endif; ?>
        </div>
        
        <button type="submit">Добавить задачу</button>
    </form>
    
    <a href="?route=task/list">← Вернуться к списку задач</a>
</body>

</html>