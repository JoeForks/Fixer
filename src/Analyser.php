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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\CS\Config\Config;
use Symfony\CS\ConfigurationResolver;
use Symfony\CS\ErrorsManager;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\Fixer as CSFixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\LintManager;

/**
 * This is the analyser class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/Fixer/blob/master/LICENSE.md> AGPL 3.0
 */
class Analyser
{
    /**
     * The fixer instance.
     *
     * @var \Symfony\CS\Fixer
     */
    protected $fixer;

    /**
     * The event dispatcher instance.
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * The stopwatch instance.
     *
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * The errors manager instance.
     *
     * @var \Symfony\CS\ErrorsManager
     */
    protected $errorsManager;

    /**
     * The lint manager instance.
     *
     * @var \Symfony\CS\LintManager
     */
    protected $lintManager;

    /**
     * The storage path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create an analyser instance.
     *
     * @param \Symfony\CS\Fixer $fixer
     *
     * @return void
     */
    public function __construct(CSFixer $fixer)
    {
        $this->fixer = $fixer;

        $this->eventDispatcher = new EventDispatcher();
        $this->stopwatch = new Stopwatch();
        $this->errorsManager = new ErrorsManager();
        $this->lintManager = new LintManager();

        $this->fixer->registerBuiltInFixers();
        $this->fixer->registerBuiltInConfigs();
        $this->fixer->setStopwatch($this->stopwatch);
        $this->fixer->setErrorsManager($this->errorsManager);
        $this->fixer->setLintManager($this->lintManager);
    }

    /**
     * Analyse the project.
     *
     * @param string $path
     *
     * @return array
     */
    public function analyse($path)
    {
        $this->stopwatch->start('fixFiles');
        $this->fixer->fix($this->getConfig($path));
        $this->stopwatch->stop('fixFiles');

        $event = $this->stopwatch->getEvent('fixFiles');

        $time = round($event->getDuration() / 1000, 3);
        $memory = round($event->getMemory() / 1024 / 1024, 3);

        return compact('time', 'memory');
    }

    /**
     * Get the project configuration.
     *
     * @param string $path
     *
     * @return \Symfony\CS\Config\Config
     */
    protected function getConfig($path)
    {
        $config = $this->getConfigFromProject($path);

        if (!is_object($config) || !is_a($config, Config::class)) {
            $config = $this->getDefaultConfig($path);
        }

        $config->setDir($path);

        $resolver = new ConfigurationResolver();
        $resolver->setAllFixers($this->fixer->getFixers())->setConfig($config)->resolve();

        $config->fixers($resolver->getFixers());

        return $config;
    }

    /**
     * Get the config from the project being analysed.
     *
     * @param string $path
     *
     * @return \Symfony\CS\Config\Config
     */
    protected function getConfigFromProject($path)
    {
        if (is_file($file = $path.'/.php_cs')) {
            return include $file;
        }
    }

    /**
     * Get the default config.
     *
     * @param string $path
     *
     * @return \Symfony\CS\Config\Config
     */
    protected function getDefaultConfig($path)
    {
        $fixers = [
            'align_double_arrow',
            'multiline_spaces_before_semicolon',
            'ordered_use',
            'phpdoc_order',
            'short_array_syntax',
        ];

        $config = Config::create()->level(FixerInterface::SYMFONY_LEVEL)->fixers($fixers);

        $config->finder(DefaultFinder::create()->notName('*.blade.php')->exclude('storage')->in($path));

        return $config;
    }
}
