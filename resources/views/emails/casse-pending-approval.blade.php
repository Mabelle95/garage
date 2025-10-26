<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle demande d'approbation</title>
</head>
<body>
<h1>Nouvelle demande d'inscription - Casse</h1>

<p>Une nouvelle casse vient de s'inscrire et attend votre approbation :</p>

<ul>
    <li><strong>Nom :</strong> {{ $casse->name }}</li>
    <li><strong>Email :</strong> {{ $casse->email }}</li>
    <li><strong>Date d'inscription :</strong> {{ $casse->created_at->format('d/m/Y à H:i') }}</li>
</ul>

<p>
    <a href="{{ route('admin.casses.pending') }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Gérer les demandes d'approbation
    </a>
</p>
</body>
</html>
