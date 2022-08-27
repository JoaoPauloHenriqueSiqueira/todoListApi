<?php

namespace Tests;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function setUser($user)
    {
      return Sanctum::actingAs($user);
    }

    public function authUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);
        return $user;
    }

    public function createTodoList($args = [])
    {
        return TodoList::factory()->create($args);
    }

    public function createTask($args = [])
    {
        return Task::factory()->create($args);
    }

}
