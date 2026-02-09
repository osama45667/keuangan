<aside class="app-sidebar text-white p-3 d-none d-lg-block" style="width:260px;">
    <div class="fw-bold fs-5 mb-4">Keuangan Pro</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.transactions.index') }}">Transaksi Keluarga</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.categories.index') }}">Kategori Keluarga</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.reports.summary') }}">Laporan Keluarga</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="{{ route('profile.edit') }}">Profil</a></li>
    </ul>
</aside>

<div class="offcanvas offcanvas-start app-sidebar text-white d-lg-none" tabindex="-1" id="appSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Keuangan Pro</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column p-3">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.transactions.index') }}">Transaksi Keluarga</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.categories.index') }}">Kategori Keluarga</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('family.reports.summary') }}">Laporan Keluarga</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('profile.edit') }}">Profil</a></li>
        </ul>
    </div>
</div>
