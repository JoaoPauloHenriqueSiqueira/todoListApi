<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const NOT_STARTED = 'NOT_STARTED';
    public const PROCESSING = 'PROCESSING';
    public const FINISHED = 'FINISHED';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->user_id = auth()->id();
        });
    }

    public function todo_list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }

    public function labels(): BelongsTo
    {
        return $this->belongsTo(Label::class);
    }
}
