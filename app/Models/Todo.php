<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todos';

    protected $fillable = [
        'title',
        'completed',
        'user_id'
    ];

    protected $casts = [
        'completed' => 'boolean'
    ];

    static function booted(): void
    {
        static::creating(function ($todo) {
            $todo->user_id = auth()->id();
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
