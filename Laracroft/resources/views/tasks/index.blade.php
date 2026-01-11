<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .mode-switcher {
            margin-bottom: 20px;
            text-align: center;
        }
        .mode-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .mode-btn.active {
            background: #FF99D3;
        }
        .mode-btn:hover {
            background: #ff66c2;
            color: white;
            text-decoration: none;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 15px;
            margin: 10px 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 5px solid #FF99D3;
        }
        li.completed {
            background-color: #D966FF;
            border-left-color: #C34CE6;
            color: white;
        }
        .add-link {
            display: inline-block;
            background-color: #FF99D3;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .add-link:hover {
            background-color: #ff66c2;
            text-decoration: none;
            color: white;
        }
        .task-toggle {
            background: none;
            border: 2px solid #FF99D3;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .task-toggle:hover {
            background-color: #FF99D3;
            color: white;
        }
        .task-toggle.completed {
            background-color: white;
            border-color: white;
            color: #D966FF;
        }
        .task-toggle.completed:hover {
            background-color: #f8f9fa;
            color: #D966FF;
        }
        .task-content {
            flex-grow: 1;
            margin: 0 20px;
            font-size: 16px;
        }
        .task-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .task-status {
            font-size: 14px;
            opacity: 0.8;
            margin-right: 15px;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background: #c82333;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .empty-state p {
            color: #6c757d;
            font-size: 18px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin: 0;
        }
    </style>
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
        <a href="{{ route('switch.mode', ['mode' => 'memory']) }}" 
           class="mode-btn {{ (session('repository_mode', 'mysql') === 'memory') ? 'active' : '' }}">
            Memory режим
        </a>
    </div>

    <div class="header">
        <h1>Список задач (
            @if((session('repository_mode', 'mysql') === 'mysql'))
                MySQL
            @elseif((session('repository_mode', 'mysql') === 'file'))
                File
            @else
                Memory
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