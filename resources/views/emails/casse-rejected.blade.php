<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Demande rejetée</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #dc3545;">Demande de compte</h2>

    <p>Bonjour {{ $casse->name }},</p>

    <p>Nous vous remercions de votre intérêt pour notre plateforme.</p>

    <p>Malheureusement, après examen de votre demande, nous ne pouvons pas approuver votre compte casse pour le moment.</p>

    <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez plus d'informations, n'hésitez pas à nous contacter.</p>

    <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
</div>
</body>
</html>
