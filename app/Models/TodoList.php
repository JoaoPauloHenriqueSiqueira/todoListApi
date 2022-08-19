<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'user_id'];

     public static function boot()
     {
         parent::boot();
         self::creating(function($item){
             $item->user_id = auth()->id();
         });
     }

    public static function allTodoListFromUser($userId)
    {
        return app(Pipeline::class)
            ->send(TodoList::query())
            ->through([
                \App\QueryFilters\Active::class
            ])
            ->thenReturn()
            ->where(['user_id'=>$userId])
            ->paginate(5);
    }
}
