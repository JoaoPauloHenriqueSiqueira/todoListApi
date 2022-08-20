<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        return TodoListResource::collection(TodoList::allTodoListFromUser(auth()->id()));
    }

    public function store(TodoListRequest $request)
    {
        $todoList = TodoList::create($request->validated());
        return new TodoListResource($todoList);
    }

    public function update(TodoList $todoList, Request $request)
    {
        if ($this->validUser($todoList)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $todoList->update($request->all());
        return new TodoListResource($todoList);

    }

    public function destroy(TodoList $todoList)
    {
        if ($this->validUser($todoList)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        $todoList->delete();
        return response('', Response::HTTP_NO_CONTENT);

    }

    private function validUser($todoList)
    {
        if ($todoList->user_id != auth()->id()) {
            return true;
        }
    }
}
