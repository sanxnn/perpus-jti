@extends('layouts.app')

@section('title', Auth::user()->role === 'admin' ? 'Manajemen Peminjaman' : 'Peminjaman Saya')

@section('content')

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- ================ MODE ADMIN ================ --}}
  @if (auth()->user()->role == 'admin')

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-gray-800">Manajemen Peminjaman</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#createLoanModal">
        <i class="fas fa-plus fa-sm"></i> Tambah Peminjaman
      </button>
    </div>

    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Member</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loans as $loan)
                <tr>
                  <td>{{ $loop->iteration + ($loans->currentPage() - 1) * $loans->perPage() }}</td>
                  <td>{{ $loan->user->name }}</td>
                  <td>{{ $loan->book->title }}</td>
                  <td>{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                  <td>{{ $loan->due_at->format('d/m/Y') }}</td>
                  <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '–' }}</td>
                  <td>
                    <span class="badge 
                                    @if($loan->status == 'borrowed') bg-warning
                                    @elseif($loan->status == 'returned') bg-success
                                    @else bg-danger @endif">
                      {{ ucfirst($loan->status) }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="#" class="btn btn-warning btn-sm" data-toggle="modal"
                      data-target="#editLoanModal{{ $loan->id }}">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                      data-target="#deleteLoanModal{{ $loan->id }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="editLoanModal{{ $loan->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Peminjaman</h5>
                        <button type="button" class="btn-close" data-dismiss="modal">x</button>
                      </div>
                      <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                          <div class="form-group">
                            <label>Member</label>
                            <select name="user_id" class="form-control" required>
                              @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ $u->id == $loan->user_id ? 'selected' : '' }}>
                                  {{ $u->name }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Buku</label>
                            <select name="book_id" class="form-control" required>
                              @foreach($books as $b)
                                <option value="{{ $b->id }}" {{ $b->id == $loan->book_id ? 'selected' : '' }}>
                                  {{ $b->title }}
                                </option>
                              @endforeach
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="borrowed_at" class="form-control"
                              value="{{ $loan->borrowed_at->format('Y-m-d') }}" required>
                          </div>
                          <div class="form-group">
                            <label>Jatuh Tempo</label>
                            <input type="date" name="due_at" class="form-control" value="{{ $loan->due_at->format('Y-m-d') }}"
                              required>
                          </div>
                          <div class="form-group">
                            <label>Tanggal Pengembalian</label>
                            <input type="date" name="returned_at" class="form-control"
                              value="{{ $loan->returned_at ? $loan->returned_at->format('Y-m-d') : '' }}">
                          </div>
                          <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                              <option value="borrowed" {{ $loan->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                              <option value="returned" {{ $loan->status == 'returned' ? 'selected' : '' }}>Returned</option>
                              <option value="overdue" {{ $loan->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync-alt"></i> Update
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                {{-- Modal Delete --}}
                <div class="modal fade" id="deleteLoanModal{{ $loan->id }}" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-dismiss="modal">x</button>
                      </div>
                      <form action="{{ route('loans.destroy', $loan->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <div class="modal-body">
                          <p>Yakin ingin menghapus peminjaman:</p>
                          <p class="fw-bold">{{ $loan->book->title }} oleh {{ $loan->user->name }}</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">Belum ada peminjaman.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          {{ $loans->links() }}
        </div>
      </div>
    </div>

    {{-- Modal Create (admin) --}}
    <div class="modal fade" id="createLoanModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Peminjaman Baru</h5>
            <button type="button" class="btn-close" data-dismiss="modal">x</button>
          </div>
          <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="form-group">
                <label>Member <span class="text-danger">*</span></label>
                <select name="user_id" class="form-control" required>
                  <option value="">-- Pilih Member --</option>
                  @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Buku <span class="text-danger">*</span></label>
                <select name="book_id" class="form-control" required>
                  <option value="">-- Pilih Buku --</option>
                  @foreach($books as $b)
                    <option value="{{ $b->id }}">{{ $b->title }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="borrowed_at" class="form-control"
                  value="{{ old('borrowed_at', now()->format('Y-m-d')) }}" required>
              </div>
              <div class="form-group">
                <label>Jatuh Tempo <span class="text-danger">*</span></label>
                <input type="date" name="due_at" class="form-control"
                  value="{{ old('due_at', now()->addDays(7)->format('Y-m-d')) }}" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  {{-- ================ MODE USER ================ --}}
  @if (auth()->user()->role == 'user')

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 text-gray-800">Peminjaman Saya</h1>
      <button class="btn btn-primary" data-toggle="modal" data-target="#createLoanUserModal">
        <i class="fas fa-plus fa-sm"></i> Ajukan Peminjaman
      </button>
    </div>

    <!-- Ringkasan -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dipinjam</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCount }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-hand-holding fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Dikembalikan</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnedCount }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card {{ $overdueCount > 0 ? 'border-left-danger' : 'border-left-secondary' }} shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div
                  class="text-xs font-weight-bold {{ $overdueCount > 0 ? 'text-danger' : 'text-secondary' }} text-uppercase mb-1">
                  Terlambat</div>
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

    @if($overdueCount > 0)
      <div class="alert alert-danger mb-4">
        <i class="fas fa-bell me-2"></i>
        <strong>Perhatian!</strong> Anda memiliki <strong>{{ $overdueCount }}</strong> peminjaman yang melewati batas waktu.
        Harap segera kembalikan buku ke petugas.
      </div>
    @endif

    <div class="tab-content">
      <!-- Tab Aktif -->
      <div class="tab-pane fade show active" id="active">
        @if($activeLoans->isEmpty())
          <div class="card shadow-sm">
            <div class="card-body text-center py-5">
              <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
              <p class="text-muted">Tidak ada buku yang sedang dipinjam.</p>
            </div>
          </div>
        @else
          <div class="row">
            @foreach($activeLoans as $loan)
              @php
                $isOverdue = \Carbon\Carbon::parse($loan->due_at)->isPast();
                $daysLeft = \Carbon\Carbon::parse($loan->due_at)->diffInDays(now(), false);
              @endphp
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 {{ $isOverdue ? 'border-left-danger' : '' }}">
                  <div class="card-body">
                    @if($loan->book->cover)
                      <img src="{{ asset('storage/' . $loan->book->cover) }}" class="img-fluid rounded mb-3" alt="Cover"
                        style="max-height: 120px; object-fit: cover;">
                    @else
                      <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="height: 120px;">
                        <i class="fas fa-book fa-2x text-muted"></i>
                      </div>
                    @endif
                    <h6 class="fw-bold">{{ Str::limit($loan->book->title, 40) }}</h6>
                    <small class="text-muted">{{ $loan->book->author ?? '—' }}</small>

                    <div class="mt-3 small">
                      <div class="d-flex justify-content-between">
                        <span><i class="fas fa-calendar me-1"></i> Pinjam</span>
                        <span>{{ $loan->borrowed_at->format('d M Y') }}</span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span><i class="fas fa-hourglass-half me-1"></i> Jatuh Tempo</span>
                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                          {{ $loan->due_at->format('d M Y') }}
                          @if (!$isOverdue && $daysLeft <= 3)
                            <span class="badge bg-warning text-dark ms-1">{{ $daysLeft }} hari</span>
                          @endif
                        </span>
                      </div>
                    </div>

                    <div class="mt-3">
                      <span class="badge {{ $isOverdue ? 'bg-danger' : 'bg-success' }} w-100 py-2">
                        <i class="fas {{ $isOverdue ? 'fa-exclamation-triangle' : 'fa-check' }} me-1"></i>
                        {{ $isOverdue ? 'Terlambat' : 'Aktif' }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Tab Riwayat -->
      <div class="tab-pane fade" id="history">
        @if($returnedLoans->isEmpty())
          <div class="card shadow-sm">
            <div class="card-body text-center py-5">
              <i class="fas fa-history fa-3x text-muted mb-3"></i>
              <p class="text-muted">Belum ada riwayat peminjaman.</p>
            </div>
          </div>
        @else
          <div class="card shadow-sm">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Buku</th>
                      <th>Dipinjam</th>
                      <th>Jatuh Tempo</th>
                      <th>Dikembalikan</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($returnedLoans as $loan)
                      <tr>
                        <td>
                          @if($loan->book->cover)
                            <img src="{{ asset('storage/' . $loan->book->cover) }}" width="36" class="rounded me-2">
                          @else
                            <i class="fas fa-book text-muted me-2"></i>
                          @endif
                          {{ Str::limit($loan->book->title, 30) }}
                        </td>
                        <td>{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                        <td>{{ $loan->due_at->format('d/m/Y') }}</td>
                        <td>{{ $loan->returned_at ? $loan->returned_at->format('d/m/Y') : '–' }}</td>
                        <td>
                          <span class="badge bg-success">Dikembalikan</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer bg-white">
              {{ $returnedLoans->links() }}
            </div>
          </div>
        @endif
      </div>
    </div>

    {{-- Modal Create (user) --}}
    <div class="modal fade" id="createLoanUserModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ajukan Peminjaman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <div class="modal-body">
              <div class="alert alert-info small">
                <i class="fas fa-info-circle me-1"></i>
                Hanya buku yang belum Anda pinjam yang tersedia.
              </div>

              <div class="form-group">
                <label>Buku <span class="text-danger">*</span></label>
                <select name="book_id" class="form-control" required>
                  <option value="">-- Pilih Buku --</option>
                  @foreach($availableBooks as $b)
                    <option value="{{ $b->id }}">{{ $b->title }} @if($b->stock) (Stok: {{ $b->stock }}) @endif</option>
                  @endforeach
                </select>
                @error('book_id')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group">
                <label>Tanggal Pinjam <span class="text-danger">*</span></label>
                <input type="date" name="borrowed_at" class="form-control"
                  value="{{ old('borrowed_at', now()->format('Y-m-d')) }}" required>
              </div>

              <div class="form-group">
                <label>Jatuh Tempo <span class="text-danger">*</span></label>
                <input type="date" name="due_at" class="form-control"
                  value="{{ old('due_at', now()->addDays(7)->format('Y-m-d')) }}" required>
                <small class="form-text text-muted">Maksimal peminjaman: 7 hari.</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i> Ajukan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  @endif

@endsection

@if (auth()->user()->role == 'user')
  @push('scripts')
    <script>
      // Auto-set due_at = borrowed_at + 7 days (user-friendly)
      document.addEventListener('DOMContentLoaded', function () {
        const borrowedInputs = document.querySelectorAll('input[name="borrowed_at"]');
        borrowedInputs.forEach(input => {
          input.addEventListener('change', function () {
            const dueInput = this.closest('form').querySelector('input[name="due_at"]');
            if (dueInput) {
              const date = new Date(this.value);
              date.setDate(date.getDate() + 7);
              dueInput.value = date.toISOString().split('T')[0];
            }
          });
        });
      });
    </script>
  @endpush
@endif