<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Connexion · {{ config('app.name', 'Gestion des ouvriers') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- ═════════ Left: brand panel ═════════ --}}
        <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden p-12 text-white"
            style="background: linear-gradient(145deg, #1a6b3a 0%, #124d29 55%, #0c381e 100%);">

            {{-- decorative circles --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full opacity-10 bg-white"></div>
            <div class="absolute top-1/2 -left-32 w-80 h-80 rounded-full opacity-5 bg-white"></div>
            <div class="absolute -bottom-32 right-10 w-96 h-96 rounded-full opacity-10 bg-white"></div>

            <div class="relative flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-white/15 backdrop-blur">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold leading-tight">Commune de Foum El Anceur</p>
                </div>
            </div>

            <div class="relative max-w-md">
                <h1 class="text-4xl font-bold leading-tight">
                    Gestion des ouvriers saisonniers
                </h1>
                <p class="mt-4 text-white/75 leading-relaxed">
                    Préparez les documents du mois : compte rendu de prise de service,
                    lettre d'engagement et état d'engagement.
                </p>

            </div>

            <p class="relative text-xs text-white/50">
                &copy; {{ date('Y') }} Commune de Foum El Anceur
            </p>
        </aside>

        {{-- ═════════ Right: form ═════════ --}}
        <main class="flex items-center justify-center px-6 py-12 bg-gray-50 lg:bg-white">
            <div class="w-full max-w-sm">

                {{-- mobile brand --}}
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white"
                        style="background:#1a6b3a;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">Commune de Foum El Anceur</p>
                        <p class="text-xs text-gray-500">Ouvriers saisonniers</p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900">Connexion</h2>
                <p class="mt-1.5 text-sm text-gray-500">Entrez vos identifiants pour accéder à l'application.</p>

                {{-- session status (e.g. password reset link sent) --}}
                @if (session('status'))
                    <div class="mt-5 px-4 py-3 text-sm rounded-lg bg-green-50 text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="mt-7 space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse
                            e-mail</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="username" placeholder="nom@exemple.com"
                                class="w-full pl-11 pr-3 py-2.5 text-sm bg-white border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 transition
                                    {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-300 focus:border-green-700 focus:ring-green-100' }}">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium hover:underline"
                                    style="color:#1a6b3a;">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full pl-11 pr-11 py-2.5 text-sm bg-white border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 transition
                                    {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-100' : 'border-gray-300 focus:border-green-700 focus:ring-green-100' }}">
                            <button type="button" id="togglePwd" tabindex="-1"
                                aria-label="Afficher le mot de passe"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeOn" class="w-[18px] h-[18px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeOff" class="w-[18px] h-[18px] hidden" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-gray-300 accent-green-700">
                        <span class="text-sm text-gray-600">Se souvenir de moi</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" id="loginBtn"
                        class="w-full inline-flex items-center justify-center gap-2 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700 disabled:opacity-70 disabled:cursor-not-allowed"
                        style="background:#1a6b3a;">
                        <svg id="loginSpin" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>
                        <span id="loginText">Se connecter</span>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    Accès réservé au personnel autorisé de la commune.
                </p>
            </div>
        </main>
    </div>

    <script>
        // show / hide password
        (function() {
            const pwd = document.getElementById('password');
            const on = document.getElementById('eyeOn');
            const off = document.getElementById('eyeOff');
            document.getElementById('togglePwd').addEventListener('click', function() {
                const show = pwd.type === 'password';
                pwd.type = show ? 'text' : 'password';
                on.classList.toggle('hidden', show);
                off.classList.toggle('hidden', !show);
            });
        })();

        // loading state on submit (prevents double clicks)
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            document.getElementById('loginSpin').classList.remove('hidden');
            document.getElementById('loginText').textContent = 'Connexion…';
        });
    </script>
</body>

</html>
