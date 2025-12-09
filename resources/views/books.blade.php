@extends('layouts.app')

@section('title', 'Manajemen Buku')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(auth()->user()->role == 'admin')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">Manajemen Buku</h1>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus fa-sm"></i> Tambah Buku
            </button>
        </div>

        <!-- Tabel Buku -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>ISBN</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($book->title, 40) }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->isbn ?? '–' }}</td>
                                    <td class="text-center">{{ $book->stock }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <a href="#" class="btn btn-warning btn-sm mr-1" data-toggle="modal"
                                            data-target="#editModal{{ $book->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <!-- Tombol Delete -->
                                        <button class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal{{ $book->id }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal: Edit Buku -->
                                <div class="modal fade" id="editModal{{ $book->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editModalLabel{{ $book->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $book->id }}">
                                                    <i class="fas fa-edit"></i> Edit Buku
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('books.update', $book->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Judul <span class="text-danger">*</span></label>
                                                        <input type="text" name="title" class="form-control"
                                                            value="{{ old('title', $book->title) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Penulis <span class="text-danger">*</span></label>
                                                        <input type="text" name="author" class="form-control"
                                                            value="{{ old('author', $book->author) }}" required>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>ISBN</label>
                                                                <input type="text" name="isbn" class="form-control"
                                                                    value="{{ old('isbn', $book->isbn) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Stok <span class="text-danger">*</span></label>
                                                                <input type="number" name="stock" class="form-control"
                                                                    value="{{ old('stock', $book->stock) }}" min="1" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Penerbit</label>
                                                                <input type="text" name="publisher" class="form-control"
                                                                    value="{{ old('publisher', $book->publisher) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Tahun Terbit</label>
                                                                <input type="number" name="year" class="form-control"
                                                                    value="{{ old('year', $book->year) }}" min="1900"
                                                                    max="{{ date('Y') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Deskripsi</label>
                                                        <textarea name="description" class="form-control"
                                                            rows="3">{{ old('description', $book->description) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-sync-alt"></i> Perbarui
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal: Hapus Buku -->
                                <div class="modal fade" id="deleteModal{{ $book->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel{{ $book->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger" id="deleteModalLabel{{ $book->id }}">
                                                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                                                </h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>Yakin ingin menghapus buku berikut?</p>
                                                    <p class="font-weight-bold text-gray-800 mb-0">
                                                        "{{ $book->title }}"
                                                    </p>
                                                    <p class="text-muted small mt-2">
                                                        ⚠️ Buku yang sedang dipinjam tidak bisa dihapus.
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                        data-dismiss="modal">Batal</button>
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
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal: Tambah Buku -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">
                            <i class="fas fa-plus"></i> Tambah Buku Baru
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('books.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Penulis <span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ISBN</label>
                                        <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stok <span class="text-danger">*</span></label>
                                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 1) }}"
                                            min="1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Penerbit</label>
                                        <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tahun Terbit</label>
                                        <input type="number" name="year" class="form-control" value="{{ old('year') }}"
                                            min="1900" max="{{ date('Y') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endif

    @if(auth()->user()->role == 'user')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">Daftar Buku</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Stok</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>

                                    <td class="text-center">
                                        @if($book->stock > 0)
                                            <span class="badge badge-success">Tersedia ({{ $book->stock }})</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#detailModal{{ $book->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Detail Buku --}}
                                <div class="modal fade" id="detailModal{{ $book->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-book"></i> Detail Buku
                                                </h5>
                                                <button class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>

                                            <div class="modal-body">
                                                <h4 class="font-weight-bold">{{ $book->title }}</h4>
                                                <p class="mb-1"><strong>Penulis:</strong> {{ $book->author }}</p>
                                                <p class="mb-1"><strong>ISBN:</strong> {{ $book->isbn ?? '–' }}</p>
                                                <p class="mb-1"><strong>Penerbit:</strong> {{ $book->publisher ?? '–' }}</p>
                                                <p class="mb-1"><strong>Tahun:</strong> {{ $book->year ?? '–' }}</p>

                                                <p class="mt-3"><strong>Deskripsi:</strong></p>
                                                <p>{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>

                                                <div class="mt-3">
                                                    @if($book->stock > 0)
                                                        <span class="badge badge-success p-2">
                                                            Tersedia ({{ $book->stock }} Buku)
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger p-2">
                                                            Stok Habis
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada buku yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif
@endsection