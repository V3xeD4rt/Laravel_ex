<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить задачу</title>
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
        <h1>Добавить новую задачу (
            @if((session('repository_mode', 'mysql') === 'mysql'))
                MySQL
            @else((session('repository_mode', 'mysql') === 'file'))
                File
            @endif
        )</h1>
    </div>
    
    <div class="form-container">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <div class="form-group">
                <label for="title">Название задачи:</label>
                <input type="text" id="title" name="title" required 
                       placeholder="Введите название задачи..." 
                       value="{{ old('title') }}">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="actions">
                <button type="submit" class="submit-btn">Добавить задачу</button>
                <a href="{{ route('tasks.index') }}" class="back-link">← Назад к списку</a>
            </div>
        </form>
    </div>
</body>
</html>