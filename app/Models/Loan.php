<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }

     // Optional: accessor untuk keamanan tambahan
    protected function dueAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value) : null,
        );
    }
}
