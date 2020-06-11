### Install

- composer install
- php artisan doctrine:migrations:migrate
- php artisan role:create:all
- php artisan db:seed `for development`

### Install websocket server for chat

- npm i -g laravel-echo-server
- laravel-echo-server init
- laravel-echo-server start `working via redis`
