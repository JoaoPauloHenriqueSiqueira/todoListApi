<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $this->postJson(route('todo-list.store'), $todoList);
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

        $this->authUser();

        $this->createTodoList();
        $this->createTodoList();

        $response = $this->getJson(route('todo-list.index'))->assertOk()->json('data');
        $this->assertEquals(2, count($response));
    }

    public function test_only_active_todo_lists_can_be_created()
    {
        $todoList1 = $this->createTodoList();
        $this->createTodoList(['active' => false]);
        $this->createTodoList(['active' => false]);
        $this->createTodoList(['active' => false]);

        $this->patchJson(route('todo-list.update', $todoList1->id), ['active' =>false]);
        $response = $this->getJson(route('todo-list.index', ['active' => false]))->assertOk()->json('data');
        $this->assertEquals(1, count($response));
    }

    public function test_sort_todo_lists_by_title_asc()
    {
        $this->createTodoList(['title' => 'CBA']);
        $this->createTodoList(['title' => '1ABC']);
        $this->createTodoList(['title' => '2CBC']);
        $this->createTodoList(['title' => 'BCA']);
        $this->createTodoList(['title' => 'ABC']);

        $response = $this->getJson(route('todo-list.index', ['sort' => 'ASC']))->assertOk()->json('data');

        $this->assertEquals('1ABC', $response[0]['title']);
        $this->assertEquals('CBA', $response[4]['title']);
    }

    public function test_sort_todo_lists_by_title_desc()
    {
        $this->createTodoList(['title' => 'CBA']);
        $this->createTodoList(['title' => '1ABC']);
        $this->createTodoList(['title' => '2CBC']);
        $this->createTodoList(['title' => 'BCA']);
        $this->createTodoList(['title' => 'ABC']);

        $response = $this->getJson(route('todo-list.index', ['sort' => 'DESC']))->assertOk()->json('data');

        $this->assertEquals('CBA', $response[0]['title']);
        $this->assertEquals('1ABC', $response[4]['title']);
    }


    public function test_user_cant_store_todo_list_without_title()
    {
        $this->withExceptionHandling();
        $todoList = TodoList::factory()->raw();
        unset($todoList['title']);
        $this->postJson(route('todo-list.store'), $todoList)->assertUnprocessable()->assertJsonValidationErrors(['title']);;
    }

    public function test_user_cant_delete_a_todo_list_from_another_user()
    {
        $todoListAnotherUser = $this->createTodoList();
        $this->authUser();

        $this->deleteJson(route('todo-list.destroy', $todoListAnotherUser->id))->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('todo_lists', ['id' => $todoListAnotherUser->id]);
    }

    public function test_user_cant_update_a_todo_list_from_another_user()
    {
        $todoListAnotherUser = $this->createTodoList();
        $this->authUser();

        $this->patchJson(route('todo-list.update', $todoListAnotherUser->id), ['title' =>'Title Updated!'])->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseHas('todo_lists', ['title' => $todoListAnotherUser->title]);
    }

}
