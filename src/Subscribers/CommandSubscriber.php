<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Fixer\Subscribers;

use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * This is the command subscriber class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CommandSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'command.runmigrations',
            'StyleCI\Fixer\Subscribers\CommandSubscriber@onRunMigrations',
            8
        );
    }

    /**
     * Handle a command.runmigrations event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onRunMigrations(Command $command)
    {
        $command->call('migrate', ['--package' => 'styleci/fixer', '--force' => true]);
    }
}
