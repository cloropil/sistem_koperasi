<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'APK') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Welcome
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('anggota.index') }}">Anggota</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('simpanan.index') }}">Simpanan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('piutang.index') }}">Piutang</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('pengajuan_pinjaman.index') }}">Pengajuan Pinjaman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dokumen.index') }}">Dokumen</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dokumen.index') }}">Dokumen</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div id="floating-calculator" class="calculator-panel">
            <div class="calculator-header">
                <span>Kalkulator</span>
                <button type="button" id="calculator-close" class="btn-close" aria-label="Close"></button>
            </div>
            <input type="text" class="calculator-display" readonly value="0">
            <div class="calculator-grid">
                <button type="button" class="calculator-button" data-action="clear">C</button>
                <button type="button" class="calculator-button" data-action="delete">DEL</button>
                <button type="button" class="calculator-button" data-value="÷">÷</button>
                <button type="button" class="calculator-button" data-value="×">×</button>
                <button type="button" class="calculator-button" data-value="7">7</button>
                <button type="button" class="calculator-button" data-value="8">8</button>
                <button type="button" class="calculator-button" data-value="9">9</button>
                <button type="button" class="calculator-button" data-value="-">-</button>
                <button type="button" class="calculator-button" data-value="4">4</button>
                <button type="button" class="calculator-button" data-value="5">5</button>
                <button type="button" class="calculator-button" data-value="6">6</button>
                <button type="button" class="calculator-button" data-value="+">+</button>
                <button type="button" class="calculator-button" data-value="1">1</button>
                <button type="button" class="calculator-button" data-value="2">2</button>
                <button type="button" class="calculator-button" data-value="3">3</button>
                <button type="button" class="calculator-button" data-action="equals">=</button>
                <button type="button" class="calculator-button zero-button" data-value="0">0</button>
                <button type="button" class="calculator-button" data-value=".">.</button>
            </div>
        </div>

        <button id="calculator-toggle" class="calculator-toggle" aria-label="Buka Kalkulator">+</button>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Number Input Cleaner Script - Pure Numbers Only -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to clean pasted number input - only allow 0-9
        function cleanNumberInput(input) {
            // Remove all non-numeric characters
            let cleaned = input.replace(/[^\d]/g, '');
            
            // Remove leading zeros except for single zero
            if (cleaned.length > 1 && cleaned.charAt(0) === '0') {
                cleaned = cleaned.replace(/^0+/, '') || '0';
            }
            
            return cleaned;
        }

        // Handle paste event for all number inputs
        document.addEventListener('paste', function(e) {
            const target = e.target;
            if (target && target.type === 'number') {
                e.preventDefault();
                
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                const cleanedValue = cleanNumberInput(pastedText);
                
                if (cleanedValue !== '') {
                    target.value = cleanedValue;
                    // Trigger input event to ensure validation works
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });

        // Handle input event to prevent invalid characters during typing
        document.addEventListener('input', function(e) {
            const target = e.target;
            if (target && target.type === 'number') {
                const originalValue = target.value;
                const cleanedValue = cleanNumberInput(originalValue);
                
                if (originalValue !== cleanedValue) {
                    target.value = cleanedValue;
                }
            }
        });

        // Handle blur event to ensure empty inputs stay empty (not 0)
        document.addEventListener('blur', function(e) {
            const target = e.target;
            if (target && target.type === 'number') {
                if (target.value === '0' && !target.required) {
                    target.value = '';
                }
            }
        });

        // Handle focus event to clear placeholder zeros
        document.addEventListener('focus', function(e) {
            const target = e.target;
            if (target && target.type === 'number') {
                if (target.value === '0' && !target.required) {
                    target.value = '';
                }
            }
        });
    });
    </script>
</body>
</html>
