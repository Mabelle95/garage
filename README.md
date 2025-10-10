# 1. Créer les migrations
php artisan make:migration create_marques_table
php artisan make:migration create_modeles_table
php artisan make:migration update_pieces_table
php artisan make:migration ensure_ville_in_pieces_table

# 2. Exécuter les migrations
php artisan migrate

# 3. Créer et exécuter le seeder

php artisan db:seed --class=MarqueModeleSeeder

php artisan db:seed --class=DatabaseSeeder

php artisan storage:link

# 4. Synchroniser les villes existantes
php artisan pieces:sync-ville

# 5. Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 6. Recréer le cache optimisé
php artisan config:cache
php artisan route:cache
