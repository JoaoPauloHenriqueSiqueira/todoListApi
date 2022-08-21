<?php

namespace Tests\Unit;

use App\Models\TodoList;
use Carbon\Carbon;
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

    public function test_an_user_cant_see_total_time_active_todo_list()
    {
        $todoList = TodoList::factory()->create(['has_timer' => true]);
        $response = $this->patchJson(route('todo-list.update', $todoList->id), ['user_id' => $this->user->id, 'title' => "Title Updated!"])->json('data');
        $this->assertEquals(false, $response['total_time']);
    }

    public function test_an_user_can_see_total_time_active_todo_list()
    {
        $todoList = TodoList::factory()->create(['created_at' => Carbon::now()->subHour(), 'has_timer' => true]);
        $response = $this->patchJson(route('todo-list.update', $todoList->id), ['active' => false])->json('data');
        $this->assertEquals('1 hour', $response['total_time']);
    }

}
