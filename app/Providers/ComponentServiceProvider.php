<?php

namespace App\Providers;

use File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Str;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modulesDirectory = base_path('app/Modules');
        $moduleDirectories = File::directories($modulesDirectory);

        foreach ($moduleDirectories as $moduleDirectory) {
            $componentDirectory = $moduleDirectory . '/Components';
            if (File::exists($componentDirectory)) {
                $this->registerComponentsFromDirectory($componentDirectory);
            }
        }
    }

    /**
     * Register Blade components from a given directory.
     *
     * @param string $directory
     */
    protected function registerComponentsFromDirectory(string $directory): void
    {
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $componentClass = $this->getComponentClassFromFile($file);
            $componentAlias = $this->getComponentAliasFromClass($componentClass);

            Blade::component($componentAlias, $componentClass);
        }
    }

    /**
     * Get the component class from a file.
     *
     * @param \SplFileInfo $file
     * @return string
     */
    protected function getComponentClassFromFile(\SplFileInfo $file): string
    {
        $relativePath = Str::after($file->getRealPath(), base_path('app') . DIRECTORY_SEPARATOR);
        $class = str_replace(
            [DIRECTORY_SEPARATOR, '.php'],
            ['\\', ''],
            $relativePath
        );

        return 'App\\' . $class;
    }

    /**
     * Get the component alias from a class name.
     *
     * @param string $class
     * @return string
     */
    protected function getComponentAliasFromClass(string $class): string
    {
        $pathParts = explode('\\', $class);
        $aliasParts = array_slice($pathParts, 2); // Skip 'App' and 'Modules'
        $alias = implode('.', array_map([Str::class, 'kebab'], $aliasParts));

        return $alias;
    }
}