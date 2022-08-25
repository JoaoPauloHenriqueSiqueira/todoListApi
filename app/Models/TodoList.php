<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pipeline\Pipeline;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id', 'description', 'has_timer', 'active'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->user_id = auth()->id();
            $item->active = true;
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public static function allTodoListFromUser($userId)
    {
        return app(Pipeline::class)
            ->send(TodoList::query())
            ->through([
                \App\QueryFilters\Active::class,
                \App\QueryFilters\Sort::class
            ])
            ->thenReturn()
            ->where(['user_id' => $userId])
            ->paginate(5);
    }
}
