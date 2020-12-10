<?php

namespace ViewLauncher\Blade;

use Illuminate\View\View;

class AssetRenderer
{
    public function js(): string
    {
        $views = array_map(function (View $view) {
            return $view->getName();
        }, ViewLauncher::$views);

        $config = app('config')['view-launcher'];

        $viewLauncherConfig = json_encode([
            'views' => $views,
            'theme' => $config['theme'],
            'editor' => $config['editor'],
            'shortcuts' => $config['shortcuts'],
        ]);

        return "<script>viewLauncher = {$viewLauncherConfig};</script>" .
            "<script src=\"{$this->getPathFromRouteName('view-launcher.js')}\" defer></script>";
    }

    public function css(): string
    {
        return "<link rel=\"stylesheet\" href=\"{$this->getPathFromRouteName('view-launcher.css')}\">";
    }

    protected function getPathFromRouteName(string $routeName): string
    {
        $route = route($routeName);

        return preg_replace('/\Ahttps?:/', '', $route);
    }
}
