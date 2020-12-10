<?php

namespace ViewLauncher\Blade;

use Illuminate\Support\Arr;

class ViewLauncher
{
    public static $views = [];

    public static function config(string $key)
    {
        static $config;

        if (!isset($config)) {
            $config = app('config')['view-launcher'];
        }

        return value(Arr::get($config, $key));
    }

    public static function assets(): string
    {
        if (static::disabled()) {
            return '';
        }

        $assetRenderer = new AssetRenderer();
        return $assetRenderer->css() . $assetRenderer->js();
    }

    public static function disabled(): bool
    {
        return !static::enabled();
    }

    public static function enabled(): bool
    {
        return static::config('enabled');
    }

    public static function editor(): string
    {
        return static::config('editor');
    }

    public static function boot(): void
    {
        static::collectViews();
    }

    protected static function collectViews()
    {
        app('events')->listen('composing:*', function ($view, $data = []) {
            if ($data) {
                $view = $data[0]; // For Laravel >= 5.4
            }

            if (in_array($view, static::$views)) {
                return;
            }

            // skip same view
            foreach (static::$views as $savedView) {
                if ($savedView->getName() === $view->getName()) {
                    return;
                }
            }

            static::$views[] = $view;
        });
    }
}
