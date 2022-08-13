<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
    <title>HoMM</title>
</head>
<body>
    <section class="main">
        <img class="svg-not" src="{{asset('img/notification.svg')}}" alt="">
        <section class="logins">
            <a class="log_twitch" href="{{ Auth::check() ? '#' : route('login', ['provider' => 'twitch']) }}">
                <img src="{{ Auth::check() ? auth()->user()->picture : asset('img/img.png') }}">
                <p>{{ Auth::check() ? 'Welcome ' . auth()->user()->name : 'Log-in with Twitch' }}</p>    
            </a>
            @if(Auth::check())
            <a href="{{ route('logout') }}" class="logout">X</a>
            @endif
        </section>
    </section>
</body>
</html>