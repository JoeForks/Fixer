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

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\CS\ErrorsManager;
use Symfony\CS\Fixer;
use Symfony\CS\LintManager;

/**
 * This is the analyser class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class Analyser
{
    /**
     * The cs fixer instance.
     *
     * @var \Symfony\CS\Fixer
     */
    protected $fixer;

    /**
     * The config resolver instance.
     *
     * @var \StyleCI\Fixer\ConfigResolver
     */
    protected $config;

    /**
     * The stopwatch instance.
     *
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * Create an analyser instance.
     *
     * @param \Symfony\CS\Fixer                      $fixer
     * @param \StyleCI\Fixer\ConfigResolver          $config
     * @param \Symfony\Component\Stopwatch\Stopwatch $stopwatch
     *
     * @return void
     */
    public function __construct(Fixer $fixer, ConfigResolver $config, Stopwatch $stopwatch)
    {
        $this->fixer = $fixer;
        $this->config = $config;
        $this->stopwatch = $stopwatch;

        $this->fixer->registerBuiltInFixers();
        $this->fixer->registerBuiltInConfigs();

        $this->fixer->setStopwatch($this->stopwatch);
        $this->fixer->setErrorsManager(new ErrorsManager());
        $this->fixer->setLintManager(new LintManager());
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
        $this->fixer->fix($this->config->resolve($path));
        $this->stopwatch->stop('fixFiles');

        $event = $this->stopwatch->getEvent('fixFiles');

        $time = round($event->getDuration() / 1000, 3);
        $memory = round($event->getMemory() / 1024 / 1024, 3);

        return compact('time', 'memory');
    }
}
