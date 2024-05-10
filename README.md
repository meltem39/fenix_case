change env.example => env

composer i

php artisan key:generate

php artisan migrate

php artisan db:seed

npm install

npm run dev

php artisan serve

php artisan l5-swagger:generate => for swagger

php artisan test => for unit tests

----- Laravel Sail -----
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

with sail: 
    php artisan sail:install
    sail artisan migrate
    sail up
    sail npm run dev
    sail test
