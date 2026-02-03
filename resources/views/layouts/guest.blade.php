<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root { color-scheme: light; }
            body { margin: 0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #0f172a; }
            .auth-shell { min-height: 100vh; display: grid; place-items: center; padding: 40px 20px; background: radial-gradient(900px 600px at 10% -10%, rgba(14, 165, 233, 0.25), transparent 60%), radial-gradient(800px 500px at 100% 20%, rgba(244, 63, 94, 0.25), transparent 55%), #0f172a; }
            .auth-grid { width: min(980px, 100%); display: grid; gap: 28px; grid-template-columns: 1.2fr 1fr; align-items: stretch; }
            .auth-panel { color: #e2e8f0; padding: 8px 8px 8px 16px; }
            .auth-brand { display: flex; gap: 14px; align-items: center; }
            .auth-logo { width: 44px; height: 44px; }
            .auth-title { font-size: 20px; font-weight: 700; }
            .auth-sub { margin-top: 12px; color: rgba(226, 232, 240, 0.8); line-height: 1.5; }
            .auth-card { background: rgba(255, 255, 255, 0.98); border-radius: 18px; padding: 28px; box-shadow: 0 25px 60px rgba(15, 23, 42, 0.35); border: 1px solid rgba(148, 163, 184, 0.35); }
            .auth-card h1 { margin: 0; font-size: 22px; }
            .auth-card p { margin: 6px 0 0; color: #64748b; }
            .auth-card label { font-size: 14px; color: #475569; }
            .auth-card input[type="email"], .auth-card input[type="password"], .auth-card input[type="text"] { width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #cbd5f5; outline: none; margin-top: 6px; font-size: 14px; }
            .auth-card input:focus { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2); }
            .auth-card button { width: 100%; border: none; border-radius: 10px; padding: 12px; font-weight: 600; background: linear-gradient(90deg, #1d4ed8, #ef4444); color: white; cursor: pointer; }
            .auth-card a { color: #0ea5e9; text-decoration: none; }
            .auth-card a:hover { text-decoration: underline; }
            .auth-card ul { list-style: none; padding-left: 0; margin: 8px 0 0; }
            .auth-footer { margin-top: 14px; font-size: 12px; color: rgba(226, 232, 240, 0.8); text-align: center; }
            @media (max-width: 900px) {
                .auth-grid { grid-template-columns: 1fr; }
                .auth-panel { display: none; }
                .auth-card { padding: 22px; }
            }
        </style>
    </head>
    <body>
        <div class="auth-shell">
            <div class="auth-grid">
                <div class="auth-panel">
                    <div class="auth-brand">
                        <x-application-logo class="auth-logo fill-current text-white" />
                        <div>
                            <div class="auth-title">Keuangan Pro</div>
                            <div class="auth-sub">Sistem Laporan Keuangan Profesional</div>
                        </div>
                    </div>
                    <p class="auth-sub">
                        Kelola pemasukan, pengeluaran, dan laporan keluarga dengan rapi, cepat, dan aman.
                    </p>
                </div>
                <div>
                    <div class="auth-card">
                        {{ $slot }}
                    </div>
                    <div class="auth-footer">Dengan masuk, Anda menyetujui kebijakan privasi dan keamanan data.</div>
                </div>
            </div>
        </div>
    </body>
</html>
