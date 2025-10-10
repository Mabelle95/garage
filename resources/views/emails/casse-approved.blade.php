<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compte approuvé</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #28a745;">Félicitations !</h2>

    <p>Bonjour {{ $casse->name }},</p>

    <p>Nous avons le plaisir de vous informer que votre compte casse a été approuvé avec succès.</p>

    <p>Vous pouvez maintenant vous connecter et accéder à toutes les fonctionnalités de la plateforme.</p>

    <div style="margin: 30px 0;">
        <a href="{{ url('/login') }}"
           style="background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
            Se connecter
        </a>
    </div>

    <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>

    <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
</div>
</body>
</html>
