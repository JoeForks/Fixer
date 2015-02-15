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

use Illuminate\Support\ServiceProvider;
use Symfony\CS\Fixer;

/**
 * This is the fixer service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class FixerServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
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
        $this->registerReportBuilder();
    }

    /**
     * Register the analyser class.
     *
     * @return void
     */
    protected function registerAnalyser()
    {
        $this->app->singleton('fixer.analyser', function () {
            $fixer = new Fixer();

            return new Analyser($fixer);
        });

        $this->app->alias('fixer.analyser', Analyser::class);
    }

    /**
     * Register the report builder class.
     *
     * @return void
     */
    protected function registerReportBuilder()
    {
        $this->app->singleton('fixer.builder', function ($app) {
            $factory = $app['git.factory'];
            $analyser = $app['fixer.analyser'];

            return new ReportBuilder($factory, $analyser);
        });

        $this->app->alias('fixer.builder', ReportBuilder::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'fixer.analyser',
            'fixer.builder',
        ];
    }
}
