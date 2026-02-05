@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="mb-4">
        <h4 class="mb-2 fw-bold">
            <i class="bi bi-person-circle me-2"></i>Pengaturan Profil
        </h4>
        <p class="text-muted small mb-0">Kelola informasi akun dan preferensi tampilan Anda</p>
    </div>

    <div class="row g-4">
        <!-- Main Profile Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Password Update -->
            <div class="card border-0 shadow-sm mb-4">
                @include('profile.partials.update-password-form')
            </div>

            <!-- Danger Zone -->
            <div class="card border-0 shadow-sm border-danger">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .profile-container {
        padding: 0.5rem;
    }

    .card {
        border-radius: 12px !important;
    }

    .row.g-4 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }
}
</style>
@endsection
