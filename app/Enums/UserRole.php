<?php

namespace App\Enums;

enum UserRole: string
{
    case CLIENT = 'client';
    case CASSE = 'casse';
    case ADMIN = 'admin'; // Optionnel pour la gestion globale

    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::CASSE => 'Casse automobile',
            self::ADMIN => 'Administrateur',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
