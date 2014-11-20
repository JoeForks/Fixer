<?php

/**
 * This file is part of Laravel Fixer by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Fixer;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\CS\Fixer as SymfonyFixer;

/**
 * This is the fixer service provider class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
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
        $this->package('graham-campbell/fixer', 'graham-campbell/fixer', __DIR__);

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
        $this->app->singleton('fixer.analyser', function ($app) {
            $fixer = new SymfonyFixer();
            $cs = new Analysers\CodeStyle($fixer);

            return new Analysers\Analyser($cs);
        });

        $this->app->alias('fixer.analyser', 'GrahamCampbell\Fixer\Analysers\Analyser');
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
            $path = $app['path.storage'].'/app/fixer';
            $options = $app['config']['graham-campbell/fixer::options'];

            return new Fixer($analyser, $path, $options);
        });

        $this->app->alias('fixer', 'GrahamCampbell\Fixer\Fixer');
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
