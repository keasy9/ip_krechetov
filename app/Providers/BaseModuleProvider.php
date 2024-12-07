<?php

declare(strict_types=1);

namespace App\Providers;

use App\View\Composers\BaseComposer;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

abstract class BaseModuleProvider extends ServiceProvider
{
    protected static ?string $composer = null;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
        $this->loadCommands($this->getCommandsPaths());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
    }

    protected function registerProviders(): void
    {
        $providerList = $this->getProviderList();

        foreach ($providerList as $provider) {
            $this->app->register($provider);
        }
    }

    protected function registerResources(): void
    {
        $moduleName      = $this->getModuleName();
        $kebabModuleName = Str::kebab($moduleName);

        $this->loadViewsFrom(base_path('modules/' . $moduleName . '/resources/views'), $kebabModuleName);
        $this->loadTranslationsFrom(base_path('modules/' . $moduleName . '/resources/lang'), $kebabModuleName);
        Blade::componentNamespace('Modules\\' . $moduleName . '\\Views\\Components', $kebabModuleName);

        ViewFacade::composer('*', BaseComposer::class);

        if (static::$composer) {
            ViewFacade::composer('*', function (View $view) use ($kebabModuleName) {
                if (Str::startsWith($view->getName(), "{$kebabModuleName}::")) {
                    app(static::$composer)->compose($view);
                }
            });
        }
    }

    /**
     * @return string[]
     */
    protected function getCommandsPaths(): array
    {
        $reflection = new ReflectionClass($this);
        $dir        = dirname($reflection->getFileName());
        $ds         = DIRECTORY_SEPARATOR;

        return [$dir . "$ds..{$ds}Console{$ds}Commands"];
    }

    /**
     * @param array $paths
     * @return void
     */
    protected function loadCommands(array $paths): void
    {
        $paths = array_unique($paths);

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = 'Modules';
        $root      = realpath(base_path()) . DIRECTORY_SEPARATOR . 'modules';

        foreach ((new Finder())->in($paths)->files() as $command) {
            $command = $namespace . str_replace(['/', '.php'], ['\\', ''], Str::after($command->getRealPath(), $root));

            if (!is_subclass_of($command, Command::class)) {
                continue;
            }

            $reflection = null;
            try {
                $reflection = new ReflectionClass($command);
            } catch (ReflectionException) {
            }
            if (!$reflection || $reflection->isAbstract()) {
                continue;
            }

            Artisan::starting(function ($artisan) use ($command) {
                $artisan->resolve($command);
            });
        }
    }

    abstract protected function getProviderList(): array;

    abstract protected function getModuleName(): string;
}
