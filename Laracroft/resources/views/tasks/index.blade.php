<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="mode-switcher">
        <a href="{{ route('switch.mode', ['mode' => 'mysql']) }}" 
           class="mode-btn {{ (session('repository_mode', 'mysql') === 'mysql') ? 'active' : '' }}">
            MySQL режим
        </a>
        <a href="{{ route('switch.mode', ['mode' => 'file']) }}" 
           class="mode-btn {{ (session('repository_mode', 'mysql') === 'file') ? 'active' : '' }}">
            File режим
        </a>
    </div>

    <div class="header">
        <h1>Список задач (
            @if((session('repository_mode', 'mysql') === 'mysql'))
                MySQL
            @else((session('repository_mode', 'mysql') === 'file'))
                File
            @endif
        )</h1>
        <a href="{{ route('tasks.create') }}" class="add-link">+ Добавить задачу</a>
    </div>
    
    @if(count($tasks) === 0)
        <div class="empty-state">
            <p>Задачи отсутствуют. Добавьте первую задачу!</p>
        </div>
    @else
        <ul>
            @foreach($tasks as $task)
                <li class="{{ $task->isCompleted() ? 'completed' : '' }}">
                    <button class="task-toggle {{ $task->isCompleted() ? 'completed' : '' }}" 
                            onclick="location.href='{{ route('tasks.toggle', $task->getId()) }}'">
                        {{ $task->isCompleted() ? "✓" : "" }}
                    </button>
                    
                    <div class="task-content">
                        {{ htmlspecialchars($task->getTitle()) }}
                    </div>
                    
                    <div class="task-actions">
                        <span class="task-status">
                            {{ $task->isCompleted() ? "Выполнено" : "Не выполнено" }}
                        </span>
                        <form method="POST" action="{{ route('tasks.destroy', $task->getId()) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" 
                                    onclick="return confirm('Удалить задачу?')">
                                Удалить
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>