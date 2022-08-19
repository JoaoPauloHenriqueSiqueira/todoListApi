<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authUser();
    }

    public function test_an_user_can_list_your_todo_list()
    {
        $this->createTodoList();
        $response = $this->getJson(route('todo-list.index'))->assertStatus(200)->json('data');
        $this->assertEquals(1, count($response));
    }

    public function test_an_user_can_store_a_new_todo_list()
    {
        $todoList = TodoList::factory()->raw();
        $this->postJson(route('todo-list.index'), $todoList);
        $this->assertDatabaseHas('todo_lists', ['user_id' => $this->user->id, 'title' => $todoList['title']]);
    }

    public function test_an_user_can_update_a_new_todo_list()
    {
        $todoList = TodoList::factory()->create();
        $this->patchJson(route('todo-list.update', $todoList->id), ['user_id' => $this->user->id, 'title' => "Title Updated!"]);
        $this->assertDatabaseHas('todo_lists', ['title' => 'Title Updated!']);
    }

    public function test_an_user_can_delete_a_todo_list()
    {
        $todoList = TodoList::factory()->create();

        $this->deleteJson(route('todo-list.destroy', $todoList->id))->assertStatus(204);
        $this->assertDatabaseMissing('todo_lists', ['title' => $todoList->title]);
    }

    public function test_fetch_all_todo_lists_from_a_user()
    {
        $this->createTodoList();
        $this->createTodoList();

        $response = $this->getJson(route('todo-list.index'))->assertOk()->json('data');
        $this->assertEquals(2, count($response));
    }

    public function test_fetch_all_active_todo_lists_from_a_user()
    {
        $this->createTodoList(['active' => false]);
        $this->createTodoList(['active' => false]);
        $this->createTodoList(['active' => false]);
        $this->createTodoList(['active' => false]);
        $this->createTodoList();
        $this->createTodoList();

        $response = $this->getJson(route('todo-list.index', ['active' => true]))->assertOk()->json('data');
        $this->assertEquals(2, count($response));
    }



//    public function test_user_cant_delete_a_todo_list_from_another_user()
//    {
//        $this->withExceptionHandling();
//
//        $user2 = $this->createUser();
//        $todoList = $this->createTodoList(['user_id' => $user2->id]);
//
//        $this->deleteJson(route('todo-list.destroy', $todoList->id));
//        $this->assertDatabaseHas('todo_lists', ['id' => $todoList->id]);
//    }

}
