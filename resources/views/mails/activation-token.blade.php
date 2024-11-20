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
    <p>Pour finaliser l'activation de votre compte {{ config('app_name') }}, veuillez cliquer sur le lien ci-dessous
        pour l'activer</p>
    <p>{{ $activationUrl }}</p>
    <p><b>Votre Mot de passe :</b> <u>{{ $password }}</u></p>
    <br>
    <p>Cordialement!</p>
    <p>{{ config('app.name') }}</p>

</html>
