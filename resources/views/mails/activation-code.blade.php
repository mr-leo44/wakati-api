<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation de compte</title>
</head>

<body>
    <p>Bonjour {{ $user->name }},</p>
    <br>
    <p>Encore un étape pour finaliser votre compte {{ config('app_name') }} !</p>
    <p>Veuillez saisir ce code pour l'activer: <br>
    <h3><strong>{{ $activationCode }}</strong></h3>
    </p>
    <br>
    <p>Ce code est valable que pendant <strong>5 minutes</strong></p>
    <p>Si vous n'avez pas demandé de code veuillez ignorer ce message</p>
    <br>
    <p>Cordialement!</p>
    <p>Support {{ config('app.name') }}</p>
</body>

</html>
