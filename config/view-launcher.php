<?php

/**
 * @return array{
 *     enabled: \Closure|bool,
 *     editor: \Closure|string,
 *     local_view_dir: \Closure|string,
 *     shortcuts: \Closure|array<string,string>,
 *     route_prefix: \Closure|string,
 * }
 */
return [
    'enabled' => function () {
        if (env('ENABLE_VIEW_LAUNCHER')) {
            return true;
        }

        $app = app();
        $config = $app['config'];

        return $config->get('app.debug') && !$app->runningInConsole() && !$app->isProduction();
    },

    'editor' => env('VIEW_LAUNCHER_EDITOR') ?? (env('IGNITION_EDITOR') ?? 'phpstorm'),

    'local_view_dir' => env('LOCAL_VIEW_DIR') ?? config('view.paths.0'),

    'theme' => env('VIEW_LAUNCHER_THEME') ?? 'dark',

    'shortcuts' => [
        'inspect' => 'a a',
        'open' => 'd',
    ],

    'route_prefix' => 'view-launcher',
];
