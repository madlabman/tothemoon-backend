<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ToTheMoon Admin</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.1/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.1/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.1/js/uikit-icons.min.js"></script>

    <!-- jQuery -->
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
</head>
<body class="uk-background-secondary uk-light" style="min-height: 100vh;">
    <div id="app">
        <nav class="uk-background-secondary uk-padding-small" uk-navbar>
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li>
                        <a href="{{ url('/') }}">
                            <h1 class="uk-heading-bullet uk-dark">ToTheMoon Admin</h1>
                        </a>
                        @auth
                            <div class="uk-navbar-dropdown uk-background-secondary uk-dark">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li><a class="nav-link" href="{{ route('fund') }}">Фонд</a></li>
                                    <li><a class="nav-link" href="{{ route('users') }}">Пользователи</a></li>
                                    <li><a class="nav-link" href="{{ route('signals') }}">Сигналы</a></li>
                                    <li><a class="nav-link" href="{{ route('payments') }}">Пополнения</a></li>
                                    <li><a class="nav-link" href="{{ route('withdraws') }}">Выплаты</a></li>
                                    <li><a class="nav-link" href="{{ route('profit') }}">Суточный доход</a></li>
                                    <li><a class="nav-link" href="{{ route('balance_history') }}">История баланса</a></li>
                                    <li><a class="nav-link" href="{{ route('news') }}">Новости</a></li>
                                    <li><a class="nav-link" href="{{ route('faq') }}">FAQ</a></li>
                                </ul>
                            </div>
                        @endauth
                    </li>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                @guest
                    <li>
                        <div>
                            <a class="uk-button uk-button-default" href="{{ route('login') }}">{{ __('Вход') }}</a>
                        </div>
                    </li>
                @else
                    <li>
                        <div>
                            <a href="#" class="uk-padding">{{ Auth::user()->name }}</a>
                            <div class="uk-navbar-dropdown uk-background-secondary uk-dark">
                                <ul class="uk-nav uk-navbar-dropdown-nav">
                                    <li class="uk-active">
                                        <a class="dropdown-item" href="{{ route('logout') }}" style="color: #fff;"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Выйти') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </li>
                @endguest
                </ul>
            </div>
        </nav>

        @if (session('status'))
            <script>
                UIkit.notification({
                    message: "{{ session('status') }}",
                    status: 'primary',
                    timeout: 3000
                });
            </script>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
