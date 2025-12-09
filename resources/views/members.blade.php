@extends('layouts.app')

@section('title', 'Manajemen Anggota')

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800">Manajemen Anggota</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">
        <i class="fas fa-plus fa-sm"></i> Tambah Anggota
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>No. HP</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->npm }}</td>
                        <td>{{ $user->department }}</td>
                        <td>{{ $user->phone ?? '–' }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-warning btn-sm mr-1"
                                data-toggle="modal"
                                data-target="#editModal{{ $user->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm"
                                data-toggle="modal"
                                data-target="#deleteModal{{ $user->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Anggota</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('members.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        @if($errors->edit->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach($errors->edit->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>NIM/NIP <span class="text-danger">*</span></label>
                                            <input type="text" name="npm" class="form-control" value="{{ old('npm', $user->npm) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>No. HP</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Program Studi <span class="text-danger">*</span></label>
                                            <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Password Baru (opsional)</label>
                                            <input type="password" name="password" class="form-control">
                                            <small class="form-text text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin ganti.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-sync-alt"></i> Perbarui
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delete -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('members.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        <p>Yakin hapus anggota:</p>
                                        <p class="font-weight-bold">{{ $user->name }} ({{ $user->npm }})</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
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
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada anggota.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Anggota Baru</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('members.store') }}" method="POST">
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
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>NIM/NIP <span class="text-danger">*</span></label>
                        <input type="text" name="npm" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Program Studi <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control" value="Teknik Informatika" required>
                    </div>
                    <div class="form-group">
                        <label>Password (opsional)</label>
                        <input type="password" name="password" class="form-control">
                        <small class="form-text text-muted">Default: `password`. Minimal 6 karakter.</small>
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
@endsection