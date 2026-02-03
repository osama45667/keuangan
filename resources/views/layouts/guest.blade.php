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
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-950">
        <div class="relative min-h-screen overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-32 -left-32 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
                <div class="absolute top-1/3 -right-32 h-80 w-80 rounded-full bg-rose-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
            </div>

            <div class="relative z-10 mx-auto flex min-h-screen max-w-5xl items-center px-6 py-10">
                <div class="grid w-full gap-8 lg:grid-cols-2">
                    <div class="hidden flex-col justify-center lg:flex">
                        <div class="inline-flex items-center gap-3 text-white">
                            <x-application-logo class="h-12 w-12 fill-current text-white" />
                            <div>
                                <div class="text-sm uppercase tracking-widest text-cyan-200/80">Keuangan Pro</div>
                                <div class="text-2xl font-semibold">Sistem Laporan Keuangan Profesional</div>
                            </div>
                        </div>
                        <p class="mt-6 text-base leading-relaxed text-slate-200/80">
                            Kelola pemasukan, pengeluaran, dan laporan keluarga dengan rapi, cepat, dan aman.
                        </p>
                        <div class="mt-8 flex items-center gap-4 text-slate-300/80">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                            Akses dari desktop & mobile
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="rounded-2xl border border-white/10 bg-white/95 p-6 shadow-2xl backdrop-blur sm:p-8">
                            <div class="mb-6 flex items-center gap-3 lg:hidden">
                                <x-application-logo class="h-10 w-10 fill-current text-slate-900" />
                                <div>
                                    <div class="text-xs uppercase tracking-widest text-slate-500">Keuangan Pro</div>
                                    <div class="text-lg font-semibold text-slate-900">Masuk ke Akun</div>
                                </div>
                            </div>
                            {{ $slot }}
                        </div>
                        <p class="mt-4 text-center text-xs text-slate-300/80">
                            Dengan masuk, Anda menyetujui kebijakan privasi dan keamanan data.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
