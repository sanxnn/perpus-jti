<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'publisher', 'year', 'stock', 'description', 'cover'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    // Scope untuk cari buku tersedia
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }
}
