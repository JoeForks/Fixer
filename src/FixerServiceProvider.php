<?php

/*
 * This file is part of StyleCI Fixer by Graham Campbell.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 */

namespace StyleCI\Fixer;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\CS\Fixer as SymfonyFixer;

/**
 * This is the fixer service provider class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/Fixer/blob/master/LICENSE.md> AGPL 3.0
 */
class FixerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('styleci/fixer', 'styleci/fixer', __DIR__);

        if ($this->app->config['graham-campbell/core::commands']) {
            $this->setupCommandSubscriber($this->app);
        }
    }

    /**
     * Setup the command subscriber.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function setupCommandSubscriber(Application $app)
    {
        $subscriber = $app->make(Subscribers\CommandSubscriber::class);

        $app['events']->subscribe($subscriber);
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
            $options = $app['config']['styleci/fixer::options'];

            return new Fixer($analyser, $path, $options);
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
