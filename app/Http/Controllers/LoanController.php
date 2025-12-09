<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $loans = Loan::with(['user', 'book'])->latest()->paginate(10);
            $users = User::where('role', 'user')->get();
            $books = Book::all();

            return view('loans', compact('loans', 'users', 'books', 'user'));
        } else {
            // User: hanya peminjaman miliknya + daftar buku tersedia untuk dipinjam
            $activeLoans = Loan::with('book')
                ->where('user_id', $user->id)
                ->whereNull('returned_at')
                ->get();

            $returnedLoans = Loan::with('book')
                ->where('user_id', $user->id)
                ->whereNotNull('returned_at')
                ->latest('returned_at')
                ->paginate(10);

            $availableBooks = Book::whereNotIn('id', function ($query) use ($user) {
                $query->select('book_id')
                    ->from('loans')
                    ->where('user_id', $user->id)
                    ->whereNull('returned_at');
            })->get();

            $activeCount = $activeLoans->count();
            $returnedCount = Loan::where('user_id', $user->id)->whereNotNull('returned_at')->count();
            $overdueCount = $activeLoans->filter(fn($l) => \Carbon\Carbon::parse($l->due_at)->isPast())->count();

            return view('loans', compact(
                'user',
                'activeLoans',
                'returnedLoans',
                'availableBooks',
                'activeCount',
                'returnedCount',
                'overdueCount'
            ));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi berbeda: admin vs user
        if ($user->role === 'admin') {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'borrowed_at' => 'required|date',
                'due_at' => 'required|date|after:borrowed_at',
            ]);

            $userId = $request->user_id;
        } else {
            // User hanya bisa pinjam buku yg tersedia (tidak sedang dipinjam)
            $request->validate([
                'book_id' => 'required|exists:books,id',
                'borrowed_at' => 'required|date',
                'due_at' => 'required|date|after:borrowed_at',
            ]);

            // Pastikan buku belum dipinjam user ini & belum dikembalikan
            $existing = Loan::where('user_id', $user->id)
                ->where('book_id', $request->book_id)
                ->whereNull('returned_at')
                ->exists();

            if ($existing) {
                return back()->withErrors(['book_id' => 'Buku ini sedang Anda pinjam.']);
            }

            $userId = $user->id;
        }

        Loan::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'borrowed_at' => $request->borrowed_at,
            'due_at' => $request->due_at,
            'status' => 'borrowed',
        ]);

        return back()->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function update(Request $request, Loan $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:borrowed_at',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
            'status' => 'required|in:borrowed,returned,overdue',
        ]);

        $loan->update([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrowed_at' => $request->borrowed_at,
            'due_at' => $request->due_at,
            'returned_at' => $request->returned_at,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Data peminjaman berhasil diupdate.');
    }

    public function destroy(Loan $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $loan->delete();
        return back()->with('success', 'Peminjaman berhasil dihapus.');
    }
}