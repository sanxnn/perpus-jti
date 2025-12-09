<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Perpus-JTI</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">


    @if (auth()->user()->role == 'admin')

        <div class="sidebar-heading">Manajemen</div>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('books.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Kelola Buku</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('members.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Kelola Anggota</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('loans.index') }}">
                <i class="fas fa-fw fa-exchange-alt"></i>
                <span>Kelola Peminjaman</span>
            </a>
        </li>

        <hr class="sidebar-divider">

    @endif


    @if (auth()->user()->role == 'user')

        <div class="sidebar-heading">Menu Pengguna</div>

        <!-- List buku yang bisa dipinjam -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('books.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Lihat Buku</span>
            </a>
        </li>

        <!-- Peminjaman user -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('loans.index') }}">
                <i class="fas fa-fw fa-book-reader"></i>
                <span>Peminjaman Saya</span>
            </a>
        </li>

        <hr class="sidebar-divider">

    @endif



</ul>
