<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить задачу</title>
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
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #FF99D3;
        }
        .submit-btn {
            background-color: #FF99D3;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #ff66c2;
        }
        .back-link {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #5a6268;
            text-decoration: none;
            color: white;
        }
        .error {
            color: #dc3545;
            margin-top: 8px;
            font-size: 14px;
            padding: 8px 12px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }
        h1 {
            color: #333;
            margin: 0;
        }
        .actions {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 20px;
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
        <h1>Добавить новую задачу (
            @if((session('repository_mode', 'mysql') === 'mysql'))
                MySQL
            @elseif((session('repository_mode', 'mysql') === 'file'))
                File
            @else
                Memory
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