<section>
    <header class="mb-4">
        <h6 class="mb-2 fw-bold text-uppercase text-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle"></i>{{ __('Delete Account') }}
        </h6>
        <p class="text-muted small mb-0">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun ini secara permanen? Tindakan ini tidak dapat dibatalkan!');">
        @csrf
        @method('delete')

        <div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
            <i class="bi bi-exclamation-circle flex-shrink-0 mt-1"></i>
            <div class="small">
                <strong>Data akan dihapus selamanya</strong><br>
                Pastikan Anda telah membuat backup data penting sebelum melanjutkan.
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">{{ __('Confirm with Password') }}</label>
            <input id="password" name="password" type="password" class="form-control form-control-lg" placeholder="Masukkan password untuk mengkonfirmasi">
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-danger btn-lg">
            <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
        </button>
    </form>
</section>
