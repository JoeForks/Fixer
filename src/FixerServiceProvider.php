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
use Illuminate\Support\ServiceProvider;
use Symfony\CS\Fixer as SymfonyFixer;

/**
 * This is the fixer service provider class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
