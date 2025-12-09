<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // ----- USER ADMIN -----
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@local',
            'password' => Hash::make('123456'), // ganti kalo mau
            'role' => 'admin',
        ]);

        // Admin ga punya member, cuma user biasa yang punya.


        // ----- USER BIASA -----
        $user = User::create([
            'name' => 'user',
            'email' => 'user@local',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);


        // ----- BUKU (3 DATA) -----
        Book::create([
            'title' => 'Pemrograman Web Dasar',
            'author' => 'Budi Santoso',
            'isbn' => '9786020010011',
            'publisher' => 'Erlangga',
            'year' => 2022,
            'stock' => 5,
            'description' => 'Belajar HTML, CSS, dan JavaScript dari dasar.'
        ]);

        Book::create([
            'title' => 'Laravel Modern',
            'author' => 'Andi Pratama',
            'isbn' => '9786024412238',
            'publisher' => 'Informatika',
            'year' => 2023,
            'stock' => 3,
            'description' => 'Panduan lengkap membangun aplikasi Laravel 10.'
        ]);

        Book::create([
            'title' => 'Algoritma dan Struktur Data',
            'author' => 'Dewi Lestari',
            'isbn' => '9789798797688',
            'publisher' => 'Gramedia',
            'year' => 2021,
            'stock' => 4,
            'description' => 'Konsep dasar algoritma dan struktur data.'
        ]);
    }
}
