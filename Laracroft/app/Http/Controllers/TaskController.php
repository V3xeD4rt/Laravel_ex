<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $tasks = $this->repository->findAll();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $task = new Task($request->title);
        $this->repository->add($task);
        
        return redirect()->route('tasks.index');
    }

    public function toggle($id)
    {
        $this->repository->toggle($id);
        return redirect()->route('tasks.index');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('tasks.index');
    }

    public function switchMode(Request $request)
    {
        $mode = $request->input('mode', 'mysql');
        if (in_array($mode, ['mysql', 'file', 'memory'])) {
            session(['repository_mode' => $mode]);
        }
        
        return redirect()->route('tasks.index');
    }
}