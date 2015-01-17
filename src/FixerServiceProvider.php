<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Fixer;

use Illuminate\Contracts\Foundation\Application;
use Orchestra\Support\Providers\ServiceProvider;
use Symfony\CS\Fixer as SymfonyFixer;

/**
 * This is the fixer service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class FixerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAnalyser();
        $this->registerFixer();
    }

    /**
     * Register the analyser class.
     *
     * @return void
     */
    protected function registerAnalyser()
    {
        $this->app->singleton('fixer.analyser', function () {
            $fixer = new SymfonyFixer();

            return new Analyser($fixer);
        });

        $this->app->alias('fixer.analyser', 'StyleCI\Fixer\Analyser');
    }

    /**
     * Register the fixer class.
     *
     * @return void
     */
    protected function registerFixer()
    {
        $this->app->singleton('fixer', function ($app) {
            $analyser = $app['fixer.analyser'];
            $path = $app['path.storage'].'/fixer';

            return new Fixer($analyser, $path);
        });

        $this->app->alias('fixer', 'StyleCI\Fixer\Fixer');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'fixer',
            'fixer.analyser',
        ];
    }
}
