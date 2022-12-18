cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan google-fonts:fetch

npm install
npm run build

php artisan serve
