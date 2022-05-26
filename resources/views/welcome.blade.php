<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HoMM</title>
</head>
<body>
    <a href=" @if(isset(auth()->user()->name)) # @else {{ route('login', ['provider' => 'twitch'])}} @endif" style='text-decoration:none;background:#9146FF;width:auto;display:flex;align-items:center;justify-content:center'>
        <img src="{{asset('img/img.png')}}" style='width:auto;height:30px' alt="">
        <p style='color:#fff;font-weight:bold;font-family:Calibri'>
        @if(isset(auth()->user()->name))
            Welcome {{auth()->user()->name}}
            <a href="{{route('logout')}}">Logout</a>
        @else
            Log-in with Twitch
        @endif
        </p>
    </a>
</body>
</html>