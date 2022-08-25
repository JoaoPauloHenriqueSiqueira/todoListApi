<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\TodoList;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        $tasks = $todo_list->tasks;
//        return TaskResource::collection($tasks);
        return $tasks;
    }
}
