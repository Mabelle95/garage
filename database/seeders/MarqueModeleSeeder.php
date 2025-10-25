<?php
// database/seeders/MarqueModeleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;
use App\Models\Modele;

class MarqueModeleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Yaris', 'Land Cruiser', 'Prado', 'Avensis', 'Auris'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Fit', 'City', 'HR-V', 'Pilot'],
            'Nissan' => ['Sentra', 'Altima', 'Pathfinder', 'Patrol', 'Qashqai', 'Juke', 'X-Trail', 'Navara'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLE', 'GLC', 'A-Class', 'CLA', 'Sprinter'],
            'BMW' => ['Série 3', 'Série 5', 'Série 7', 'X3', 'X5', 'X1', 'Série 1'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'Tiguan', 'Touareg', 'Jetta'],
            'Peugeot' => ['206', '207', '208', '307', '308', '407', '508', '3008', '5008', 'Partner'],
            'Renault' => ['Clio', 'Megane', 'Scenic', 'Duster', 'Captur', 'Kangoo', 'Logan'],
            'Ford' => ['Focus', 'Fiesta', 'Mondeo', 'Explorer', 'Ranger', 'Escape', 'Edge'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'i10', 'i20', 'Accent'],
            'Kia' => ['Sportage', 'Sorento', 'Rio', 'Picanto', 'Cerato', 'Soul'],
            'Mazda' => ['Mazda3', 'Mazda6', 'CX-5', 'CX-3', 'CX-9', 'MX-5'],
            'Mitsubishi' => ['Lancer', 'Outlander', 'Pajero', 'L200', 'ASX', 'Montero'],
            'Audi' => ['A3', 'A4', 'A6', 'Q3', 'Q5', 'Q7', 'A8'],
            'Chevrolet' => ['Cruze', 'Malibu', 'Equinox', 'Traverse', 'Silverado', 'Aveo'],
            'Suzuki' => ['Swift', 'Vitara', 'Jimny', 'Alto', 'Baleno', 'Celerio'],
            'Isuzu' => ['D-Max', 'MU-X', 'Trooper', 'NPR'],
            'Jeep' => ['Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Renegade'],
            'Land Rover' => ['Range Rover', 'Discovery', 'Defender', 'Evoque', 'Freelander'],
            'Subaru' => ['Impreza', 'Forester', 'Outback', 'Legacy', 'XV'],
            'Lexus' => ['IS', 'ES', 'GS', 'RX', 'NX', 'LX'],
            'Volvo' => ['S60', 'S90', 'XC60', 'XC90', 'V40', 'V60'],
            'Citroën' => ['C3', 'C4', 'C5', 'Berlingo', 'Jumper', 'Xsara'],
            'Opel' => ['Corsa', 'Astra', 'Insignia', 'Mokka', 'Zafira'],
            'Fiat' => ['Punto', '500', 'Panda', 'Tipo', 'Doblo', 'Ducato'],
            'Seat' => ['Ibiza', 'Leon', 'Ateca', 'Arona', 'Toledo'],
            'Skoda' => ['Octavia', 'Fabia', 'Superb', 'Kodiaq', 'Kamiq'],
            'Dodge' => ['Charger', 'Challenger', 'Durango', 'Journey', 'Ram'],
            'Ssangyong' => ['Tivoli', 'Korando', 'Rexton', 'Actyon'],
            'Dacia' => ['Sandero', 'Logan', 'Duster', 'Lodgy'],
            'JAC' => ['S3', 'S5', 'T6', 'J4', 'Refine'],
            'Haval' => ['H2', 'H6', 'H9', 'Jolion', 'F7'],
            'Chery' => ['Tiggo', 'QQ', 'Arrizo', 'Fulwin'],
            'Geely' => ['Emgrand', 'Coolray', 'Atlas', 'GC6'],
            'Mahindra' => ['Scorpio', 'XUV500', 'Thar', 'Bolero'],
            'Tata' => ['Safari', 'Indica', 'Nexon', 'Harrier'],
            'Great Wall' => ['Wingle', 'Haval', 'Steed', 'Voleex'],
            'Foton' => ['Tunland', 'View', 'Aumark'],
            'Dongfeng' => ['Rich', 'S30', 'AX7'],
            'BYD' => ['F3', 'S6', 'Tang', 'Song'],
            'BAIC' => ['D20', 'BJ40', 'X25', 'X55'],
            'Lifan' => ['520', 'X60', '620', '720'],
            'Brilliance' => ['V5', 'H530', 'H330'],
            'ZX Auto' => ['Grand Tiger', 'Admiral', 'Landmark'],
            'Jinbei' => ['Haise', 'Grace', 'SY6498'],
            'Gonow' => ['Troy', 'GA200', 'Way'],
            'Changan' => ['CS35', 'CS75', 'Eado', 'Alsvin'],
            'JAC Motors' => ['iEV7S', 'S2', 'S7'],
            'Zotye' => ['T600', 'Z100', 'Z300'],
            'Proton' => ['Saga', 'Persona', 'X70', 'Exora'],
        ];

        foreach ($data as $marqueName => $modeles) {
            $marque = Marque::create(['nom' => $marqueName]);

            foreach ($modeles as $modeleName) {
                Modele::create([
                    'marque_id' => $marque->id,
                    'nom' => $modeleName,
                ]);
            }
        }
    }
}
