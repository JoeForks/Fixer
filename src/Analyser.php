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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\CS\Config\Config;
use Symfony\CS\ConfigurationResolver;
use Symfony\CS\ErrorsManager;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\Fixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\LintManager;

/**
 * This is the analyser class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
    public function __construct(Fixer $fixer)
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
        $fixers = [
            '-phpdoc_no_empty_return',
            'align_double_arrow',
            'multiline_spaces_before_semicolon',
            'ordered_use',
            'phpdoc_order',
            'short_array_syntax',
        ];

        $config = Config::create()->level(FixerInterface::SYMFONY_LEVEL)->fixers($fixers);

        $config->finder(DefaultFinder::create()->notName('*.blade.php')->exclude('storage')->in($path));

        $config->setDir($path);

        $resolver = new ConfigurationResolver();
        $resolver->setAllFixers($this->fixer->getFixers())->setConfig($config)->resolve();

        $config->fixers($resolver->getFixers());

        return $config;
    }
}
