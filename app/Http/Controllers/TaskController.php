<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contract\ValidOwner;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    use ValidOwner;

    public function index(TodoList $todo_list)
    {
        if ($this->valid($todo_list)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        return $todo_list->tasks;
        return TaskResource::collection($tasks->all());
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        if ($this->valid($todo_list)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $task = $todo_list->tasks()->create($request->all());

        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        if ($this->valid($task)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $task = $task->update($request->only(['title', 'description']));
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }


}
