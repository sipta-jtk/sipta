# Installation
## How to Run the Application
1. Copy ```.env.example``` and rename it to ```.env```.
2. Configure the database username and password in the ```.env``` file.
3. Run the command ```docker compose up --build -d```.
4. The application can be accessed at ```127.0.0.1:8000```.
5. You don't need to recreate container if you had a changes. It'll update automatically.

- If you don't want to run the seeder because it will replace your existing data in MySQL, simply set ```RUN_SEEDER=false``` in the ```.env``` file.
- If database can't connect to app service, run the command ```docker compose down -v``` first.
- If you encounter an error with message 'ERROR [app internal] load build context, delete vendor folder and run the command ```Get-ChildItem -Recurse | ForEach-Object { icacls $_.FullName /reset }```.

## How to Create a Module
1. Run the command ```docker exec -it sipta-app-dev php artisan make:modul ModulName```.
2. Inside every module, you will find:
    - Routes
    - Controller
    - Views

## Database Table and Seeder Configuration
Database migration and seeding are automatically performed when you run the application with Docker. However, if you want to do it manually, follow these steps:
1. Run the command ```docker exec -it sipta-app-dev php artisan migrate``` (This will run the migration)
2. Run the command ```docker exec -it sipta-app-dev php artisan db:seed``` (This will seed the database)

If you encounter an error, do this:
1. Run the command ```docker exec -it sipta-app-dev php artisan db:wipe --force``` (This will drop the current database.)
2. Run the command ```docker exec -it sipta-app-dev php artisan artisan:migrate --seed --force``` (This will run the migrations and seed the database.)

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
