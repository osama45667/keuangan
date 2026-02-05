<x-guest-layout>
    <div class="mb-3">
        <h1>Masuk</h1>
        <p>Gunakan akun Anda untuk mengakses dashboard.</p>
    </div>

    @if (session('status'))
        <div class="auth-alert success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>

        <div class="auth-field">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>

        <div class="auth-actions">
            <label class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
        </div>

        <div class="auth-field">
            <button type="submit">Log in</button>
        </div>
    </form>
</x-guest-layout>
