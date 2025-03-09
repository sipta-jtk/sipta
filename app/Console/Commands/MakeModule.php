<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module with controllers, views, and routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $basePath = base_path("app/Modules/{$name}");

        $filesystem = new Filesystem();

        // Create module directory
        if (!$filesystem->exists($basePath)) {
            $filesystem->makeDirectory($basePath, 0755, true);
            $this->info("Module {$name} created.");
        }

        $controllersPath = "{$basePath}/Controllers";
        if (!$filesystem->exists($controllersPath)) {
            $filesystem->makeDirectory($controllersPath, 0755, true);
        }

        // Create Controller
        $controllerPath = "{$basePath}/Controllers/{$name}Controller.php";
        if (!$filesystem->exists($controllerPath)) {
            $controllerTemplate = <<<PHP
<?php

namespace App\Modules\\{$name}\\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;

class {$name}Controller extends Controller
{
    public function index(): View
    {
        return view('{$name}.views.view');
    }
}
PHP;
            $filesystem->put($controllerPath, $controllerTemplate);
            $this->info("Controller {$name}Controller.php created.");
        }

        $viewTemplate = <<<PHP
        @extends('adminlte::page')
        
        @section('title', '{$name}')
        
        @section('content_header')
            <h1>{$name}</h1>
        @stop
        
        @section('content')
            <p>Welcome to {$name} Page.</p>
        @stop
        
        @section('css')
            {{-- Add here extra stylesheets --}}
            {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
        @stop

        @section('js')
            <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
        @stop
        PHP;

        // Create Views Folder and Default View
        $viewsPath = "{$basePath}/views";
        if (!$filesystem->exists($viewsPath)) {
            $filesystem->makeDirectory($viewsPath, 0755, true);
            $filesystem->put("{$viewsPath}/view.blade.php", $viewTemplate);
            $this->info("View folder and view.blade.php created.");
        }

        // Create Routes File
        $routesPath = "{$basePath}/routes.php";
        if (!$filesystem->exists($routesPath)) {
            $routesTemplate = <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Modules\\{$name}\\Controllers\\{$name}Controller;

Route::get('/{$name}', [{$name}Controller::class, 'index']);
PHP;
            $filesystem->put($routesPath, $routesTemplate);
            $this->info("Routes file created.");
        }
    }
}
