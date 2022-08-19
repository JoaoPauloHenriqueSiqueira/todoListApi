<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::allTodoListFromUser(auth()->id());
        return $list;
    }

    public function store(Request $request)
    {
        return TodoList::create($request->all());
    }

    public function update(TodoList $todoList, Request $request)
    {
        if ($this->validUser($todoList)) {
            return response('', Response::HTTP_NOT_FOUND);
        }

        return $todoList->update($request->all());
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
