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
    <h3><b>{{ $activationCode }}</b></h3>
    </p>
    <br>
    Si ce compte ne vous concerne pas prière d'ignorer ce mail!.
    <br>
    <p>Cordialement!</p>
    <p>{{ config('app.name') }}</p>
</body>

</html>
