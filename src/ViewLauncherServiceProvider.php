<?php

namespace ViewLauncher\Blade;

use Illuminate\Routing\Router;
use Yaquawa\LookAlike\LookAlike;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use ViewLauncher\Blade\Controllers\AssetController;

class ViewLauncherServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerBladeDirectives();

        if (ViewLauncher::disabled()) {
            return;
        }

        $this->rebindBladeCompiler();
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('viewLauncherAssets', function () {
            return '{!! \ViewLauncher\Blade\ViewLauncher::assets() !!}';
        });
    }

    protected function rebindBladeCompiler()
    {
        if ($this->hasCustomBladeCompilerRegistered()) {
            $this->generateBladeCompilerClassDynamically();
        }

        $originalBladeCompiler = $this->app->make('blade.compiler');
        Blade::clearResolvedInstances();

        $this->app->singleton('blade.compiler', function ($app) use ($originalBladeCompiler) {
            $bladeCompiler = new BladeCompiler($app['files'], $app['config']['view.compiled']);
            (new LookAlike($bladeCompiler))->syncProperties($originalBladeCompiler);

            return $bladeCompiler;
        });
    }

    protected function hasCustomBladeCompilerRegistered(): bool
    {
        if (!$this->app->bound('blade.compiler')) {
            return false;
        }

        return get_class($this->app->make('blade.compiler')) !== \Illuminate\View\Compilers\BladeCompiler::class;
    }

    protected function generateBladeCompilerClassDynamically(): void
    {
        $customBladeCompilerClass = '\\' . get_class($this->app->make('blade.compiler'));
        $code = str_replace(
            ['<?php', '\Illuminate\View\Compilers\BladeCompiler'],
            ['', $customBladeCompilerClass],
            file_get_contents(__DIR__ . '/BladeCompiler.php')
        );
        eval($code);
    }

    protected function mergeConfig()
    {
        $configPath = __DIR__ . '/../config/view-launcher.php';
        $this->mergeConfigFrom($configPath, 'view-launcher');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (ViewLauncher::disabled()) {
            return;
        }

        $configPath = __DIR__ . '/../config/view-launcher.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $routeConfig = [
            'prefix' => $this->app['config']->get('view-launcher.route_prefix'),
        ];

        $this->getRouter()->group($routeConfig, function ($router) {
            $router->get('assets/view-launcher.css', [AssetController::class, 'css'])->name('view-launcher.css');
            $router->get('assets/view-launcher.js', [AssetController::class, 'js'])->name('view-launcher.js');
        });

        ViewLauncher::boot();
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('view-launcher.php');
    }
}
