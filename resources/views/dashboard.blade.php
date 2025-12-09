@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

  @if($mode === 'admin')
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-gray-800">Dashboard Admin</h1>
    </div>

    <div class="row">
      <!-- Total Buku -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Buku</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-book fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Anggota -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Total Anggota</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMembers }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Peminjaman Aktif -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Peminjaman Aktif</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $loansActive }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Terlambat Dikembalikan -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                  Terlambat Dikembalikan</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $loansOverdue }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart (opsional) -->
    <div class="row">
      <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4">

          <!-- Card Header -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Statistik Peminjaman Bulan Ini</h6>
          </div>

          <!-- Card Body -->
          <div class="card-body" style="height: 700px;">
            <div class="chart-bar" style="height: 100px">
              <canvas id="loanChart"></canvas>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="row">

      <!-- Chart Statistik Pengembalian -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Statistik Pengembalian (7 Hari)</h6>
          </div>
          <div class="card-body" style="height: 350px;">
            <canvas id="returnChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Chart Buku Terpopuler -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Top 5 Buku Paling Dipinjam</h6>
          </div>
          <div class="card-body" style="height: 350px;">
            <canvas id="topBooksChart"></canvas>
          </div>
        </div>
      </div>

    </div>

  @endif


  @if($mode === 'user')

    <!-- Ringkasan Statistik -->
    <div class="row mb-4">
      <!-- Total Dipinjam -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Dipinjam
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBorrowed }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-book-reader fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sedang Dipinjam -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Sedang Dipinjam
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Telah Dikembalikan -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Sudah Dikembalikan
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnedLoansCount }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Terlambat -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card {{ $overdueCount > 0 ? 'border-left-danger' : 'border-left-secondary' }} shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div
                  class="text-xs font-weight-bold {{ $overdueCount > 0 ? 'text-danger' : 'text-secondary' }} text-uppercase mb-1">
                  Terlambat
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overdueCount }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="d-flex flex-wrap">
          <a href="{{ route('books.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-search me-1"></i> Cari & Pinjam Buku
          </a>
        </div>
      </div>
    </div>

    <!-- Peringatan jika ada keterlambatan -->
    @if($overdueCount > 0)
      <div class="alert alert-danger d-flex align-items-center mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <div>
          Anda memiliki <strong>{{ $overdueCount }}</strong> buku yang melewati batas waktu pengembalian. Harap segera
          kembalikan untuk menghindari sanksi.
        </div>
      </div>
    @endif

    <!-- Daftar Buku Sedang Dipinjam -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
          <i class="fas fa-clock me-2"></i> Buku Sedang Dipinjam ({{ $activeLoans->count() }})
        </h6>
        @if($activeLoans->count() > 0)
          <small class="text-muted">{{ $activeLoans->count() }} buku</small>
        @endif
      </div>
      <div class="card-body">
        @if($activeLoans->isEmpty())
          <div class="text-center py-4">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Belum ada buku yang sedang dipinjam.</p>
            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary mt-2">
              <i class="fas fa-plus me-1"></i> Pinjam Buku Sekarang
            </a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th scope="col">Buku</th>
                  <th scope="col">Dipinjam</th>
                  <th scope="col">Jatuh Tempo</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($activeLoans as $loan)
                  @php
                    $isOverdue = $loan->due_at->isPast();
                    $daysLeft = $loan->due_at->diffInDays(now(), false);
                  @endphp
                  <tr>
                    <td>
                      @if($loan->book->cover)
                        <img src="{{ asset('storage/' . $loan->book->cover) }}" width="40" class="rounded me-2" alt="Cover">
                      @else
                        <i class="fas fa-book fa-fw text-muted me-2"></i>
                      @endif
                      <span class="fw-bold">{{ Str::limit($loan->book->title, 35) }}</span>
                      <br>
                      <small class="text-muted">{{ $loan->book->author ?? '—' }}</small>
                    </td>
                    <td>{{ $loan->borrowed_at->format('d M Y') }}</td>
                    <td>
                      <span class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                        {{ $loan->due_at->format('d M Y') }}
                      </span>
                      @if(!$isOverdue && $daysLeft <= 3)
                        <span class="badge bg-warning text-dark ms-1">Segera!</span>
                      @endif
                    </td>
                    <td>
                      @if($isOverdue)
                        <span class="badge bg-danger">Terlambat</span>
                      @else
                        <span class="badge bg-success">Aktif</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

  @endif


@endsection

@if ($mode === 'admin')


  @push('scripts')
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <script>
      const ctx = document.getElementById("loanChart");

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($days) !!}, // contoh: ["20 Jan", "21 Jan", ...]
          datasets: [{
            label: "Peminjaman",
            backgroundColor: "#4e73df",
            borderColor: "#4e73df",
            data: {!! json_encode($totals) !!}, // contoh: [2,5,1,0,3,4,2]
          }],
        },
      });

      // ==========================
      // Chart 2 - Pengembalian
      // ==========================
      new Chart(document.getElementById("returnChart"), {
        type: 'line',
        data: {
          labels: {!! json_encode($daysReturn) !!},
          datasets: [{
            label: "Pengembalian",
            borderColor: "#1cc88a",
            backgroundColor: "rgba(28, 200, 138, .2)",
            data: {!! json_encode($totalsReturn) !!},
            fill: true,
            tension: 0.4
          }],
        },
      });


      // ==========================
      // Chart 3 - Top Books
      // ==========================
      new Chart(document.getElementById("topBooksChart"), {
        type: 'bar',
        data: {
          labels: {!! json_encode($topBookNames) !!},
          datasets: [{
            label: "Jumlah Dipinjam",
            backgroundColor: "#e74a3b",
            borderColor: "#e74a3b",
            data: {!! json_encode($topBookTotals) !!},
          }],
        },
        options: {
          indexAxis: 'y',
        }
      });
    </script>

  @endpush


@endif