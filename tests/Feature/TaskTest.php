<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_user_can_fetch_all_tasks_from_a_todo_list()
    {
        $todoList = $this->createTodoList();
        $this->createTask(['todo_list_id' => $todoList->id]);
        $this->createTask(['todo_list_id' => $todoList->id]);

        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json('data');
        $this->assertEquals(2, count($response));
    }

    public function test_user_can_fetch_only_your_tasks_from_a_todo_list()
    {
        $user = $this->user;
        $todoList = $this->createTodoList();
        $this->createTask(['todo_list_id' => $todoList->id]);
        $this->createTask(['todo_list_id' => $todoList->id]);

        $this->authUser();
        $todoList2 = $this->createTodoList();
        $this->createTask(['todo_list_id' => $todoList2->id]);

        $this->setUser($user);
        $response = $this->getJson(route('todo-list.task.index', $todoList->id))->assertOk()->json('data');
        $this->assertEquals(2, count($response));
    }

    public function test_user_can_store_task_from_a_todo_list()
    {
        $todoList = $this->createTodoList();
        $task = Task::factory()->raw();
        $this->postJson(route('todo-list.task.store', $todoList->id), $task)->assertCreated()->json('data');
        $this->assertDatabaseHas('tasks', ['title' => $task['title']]);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create();

        $newTitle = Str::generateRandomString();
        $newDescription = Str::generateRandomString(30);

        $this->patchJson(route('task.update', $task->id), ['title' => $newTitle,'description' => $newDescription])->assertOk()->json('data');
        $this->assertDatabaseHas('tasks', ['title' => $newTitle, 'description' => $newDescription]);
    }

    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create();
        $this->deleteJson(route('task.update', $task->id))->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }


}
