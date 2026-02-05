<nav class="navbar navbar-light app-navbar px-3">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar">
            &#9776;
        </button>
        <span class="navbar-brand mb-0 h1">Sistem Laporan Keuangan Profesional</span>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-secondary btn-sm">Logout</button>
    </form>
</nav>
