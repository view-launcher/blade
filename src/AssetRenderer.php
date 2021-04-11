<?php

namespace ViewLauncher\Blade;

use Illuminate\View\View;

class AssetRenderer
{
    public function js(): string
    {
        $config = app('config')['view-launcher'];

        $viewLauncherConfig = json_encode([
            'theme' => $config['theme'],
            'editor' => $config['editor'],
            'shortcuts' => $config['shortcuts'],
        ]);

        return "<script src=\"{$this->getPathFromRouteName('view-launcher.js')}\" defer></script>" .
            "<script> document.addEventListener('DOMContentLoaded',function(){window['view-launcher'].viewLauncher({$viewLauncherConfig})})</script>";
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
