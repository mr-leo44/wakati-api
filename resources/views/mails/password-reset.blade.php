<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
</head>

<body>
    <p>Bonjour {{ $user->name }},</p>
    <br>
    <p>Voici le code réinitialisation de votre mot de passe !</p>
    <h3><strong>{{ $resetCode }}</strong></h3>
    </p>
    <p>Ce code est valable que pendant <strong>5 minutes</strong></p>
    <p>Si vous n'avez pas demandé de code veuillez ignorer ce message</p>
    <br>
    <p>Cordialement!</p>
    <p>Support {{ config('app.name') }}</p>
</body>

</html>