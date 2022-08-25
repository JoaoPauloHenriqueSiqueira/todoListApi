<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }
    public function test_user_can_fetch_all_tasks_from_a_todo_list(){
        $todoList = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $todoList->id]);

        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json();
        dd($response);
    }

}
