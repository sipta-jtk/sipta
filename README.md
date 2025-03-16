# Installation
1. run command ```composer i```
2. copy .env.example and rename to .env
3. set up the database configuration (make sure its same with the port on docker)
4. run command ```php artisan key:generate```
5. run command ```docker compose up -d```
6. and finally, run the app with ```php artisan ser```
7. make module with ```php artisan make:modul ModulName```
    in modul included : -routes, controllers and views

## Database Table and Seeder Configuration
1. run command ```php artisan db:wipe``` (Drop Current Database)
2. run command ```php artisan migrate:refresh --seed```

<br>
<br>


# Technology are Used
## Documentation of AdminLTE Template Usage
- https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/usage.html
- config on : config/adminlte.php
  
## Spatie Response Cache
- https://github.com/spatie/laravel-responsecache 
- https://www.tiktok.com/@yogameleniawan/photo/7467150735872937223 

## Logging
- Laravel Observer

## Authentication
- Laravel Fortify
