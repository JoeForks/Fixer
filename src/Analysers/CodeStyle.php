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

namespace GrahamCampbell\Fixer\Analysers;

use Symfony\CS\Config\Config;
use Symfony\CS\ConfigurationResolver;
use Symfony\CS\ErrorsManager;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\Fixer;
use Symfony\CS\FixerInterface;
use Symfony\CS\LintManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * This is the code style analyser class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class CodeStyle
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
     * @param string            $path
     *
     * @return void
     */
    public function __construct(Fixer $fixer, $path)
    {
        $this->fixer = $fixer;
        $this->path = $path;

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
     * Analyse the commit.
     *
     * @param string $commit
     *
     * @return array
     */
    public function analyse($commit)
    {
        $this->stopwatch->start('fixFiles');
        $changed = $this->fixer->fix($this->getConfig($commit), true, true);
        $this->stopwatch->stop('fixFiles');

        $files = [];

        $fixEvent = $this->stopwatch->getEvent('fixFiles');

        foreach ($this->stopwatch->getSectionEvents('fixFile') as $file => $event) {
            if ('__section__' === $file) {
                continue;
            }

            $name = substr($file, strrpos($file, $commit) + strlen($commit) + 1);

            $files[$name]['time'] = round($event->getDuration() / 1000, 3);
        }

        foreach ($changed as $file => $result) {
            $name = substr($file, strrpos($file, $commit) + strlen($commit) + 1);
        }

        return [
            'time'   => round($fixEvent->getDuration() / 1000, 3),
            'memory' => round($fixEvent->getMemory() / 1024 / 1024, 3),
            'files'  => $files,
        ];
    }

    protected function getConfig($commit)
    {
        $path = $this->path.'/'.$commit;

        $fixers = [
            '-yoda_conditions',
            'align_double_arrow',
            'multiline_spaces_before_semicolon',
            'ordered_use',
            'short_array_syntax',
        ];

        $config = Config::create()->level(FixerInterface::SYMFONY_LEVEL)->fixers($fixers);
        $config->finder(DefaultFinder::create()->notName('*.blade.php')->in($path));
        $config->setDir($path);

        $resolver = new ConfigurationResolver();
        $resolver->setAllFixers($this->fixer->getFixers())->setConfig($config)->resolve();

        $config->fixers($resolver->getFixers());

        return $config;
    }
}
