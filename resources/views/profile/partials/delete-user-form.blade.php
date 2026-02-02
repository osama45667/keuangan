<section>
    <header class="mb-3">
        <h5 class="mb-1">{{ __('Delete Account') }}</h5>
        <div class="text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </div>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun ini secara permanen?');">
        @csrf
        @method('delete')

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <button class="btn btn-danger">{{ __('Delete Account') }}</button>
    </form>
</section>
