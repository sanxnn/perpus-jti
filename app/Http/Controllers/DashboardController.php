<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ============================
        // DASHBOARD ADMIN
        // ============================
        if ($user->role === 'admin') {

            // 1. Statistik Peminjaman
            $dailyLoans = Loan::selectRaw('DATE(borrowed_at) as day, COUNT(*) as total')
                ->where('borrowed_at', '>=', now()->subDays(6))
                ->groupBy('day')
                ->pluck('total', 'day');

            $days = [];
            $totals = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $days[] = now()->subDays($i)->format('d M');
                $totals[] = $dailyLoans[$date] ?? 0;
            }

            // 2. Statistik Pengembalian
            $dailyReturns = Loan::selectRaw('DATE(returned_at) as day, COUNT(*) as total')
                ->whereNotNull('returned_at')
                ->where('returned_at', '>=', now()->subDays(6))
                ->groupBy('day')
                ->pluck('total', 'day');

            $daysReturn = [];
            $totalsReturn = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $daysReturn[] = now()->subDays($i)->format('d M');
                $totalsReturn[] = $dailyReturns[$date] ?? 0;
            }

            // 3. Top Books
            $topBooks = Loan::with('book')
                ->selectRaw('book_id, COUNT(*) AS total')
                ->groupBy('book_id')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            return view('dashboard', [
                'mode' => 'admin',
                'days' => $days,
                'totals' => $totals,
                'daysReturn' => $daysReturn,
                'totalsReturn' => $totalsReturn,
                'topBookNames' => $topBooks->map(fn($b) => $b->book->title),
                'topBookTotals' => $topBooks->map(fn($b) => $b->total),

                'totalBooks' => Book::count(),
                'totalMembers' => User::where('role', 'user')->count(),
                'loansActive' => Loan::whereNull('returned_at')->count(),
                'loansOverdue' => Loan::where('status', 'overdue')
                    ->whereDate('due_at', '<', now())
                    ->count(),
            ]);
        }

        $user = Auth::user();

        $activeLoans = Loan::with('book')
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->get();

        $totalBorrowed = Loan::where('user_id', $user->id)->count();
        $returnedLoansCount = Loan::where('user_id', $user->id)
            ->whereNotNull('returned_at')
            ->count();

        $overdueCount = $activeLoans->filter(function ($loan) {
            return $loan->due_at->isPast();
        })->count();

        return view('dashboard', [
            'mode' => 'user',
            'activeLoans' => $activeLoans,
            'totalBorrowed' => $totalBorrowed,
            'returnedLoansCount' => $returnedLoansCount,
            'overdueCount' => $overdueCount,
        ]);
    }
}
