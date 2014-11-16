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

use GrahamCampbell\Fixer\Models\Commit;
use Symfony\CS\Config\Config;
use Symfony\CS\ErrorsManager;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\Fixer;
use Symfony\CS\LintManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * This is the analyser class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
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
     * @param string $repo
     * @param string $commit
     *
     * @return \GrahamCampbell\Fixer\Models\Commit
     */
    public function analyse($repo, $commit)
    {
        $this->stopwatch->start('fixFiles');
        $changed = $this->fixer->fix($this->getConfig($commit), true, true);
        $this->stopwatch->stop('fixFiles');

        $files = [];

        foreach ($changed as $file => $result) {
            $files[] = ['name'     => $file, 'appliedFixers'     => $result['appliedFixers'], 'diff'     => $result['diff']];
        }

        $fixEvent = $this->stopwatch->getEvent('fixFiles');

        $data = [
            'files'  => $files,
            'memory' => round($fixEvent->getMemory() / 1024 / 1024, 3),
            'time'   => [
                'total' => round($fixEvent->getDuration() / 1000, 3),
            ],
        ];

        $fileTime = [];

        foreach ($this->stopwatch->getSectionEvents('fixFile') as $file => $event) {
            if ('__section__' === $file) {
                continue;
            }

            $fileTime[$file] = round($event->getDuration() / 1000, 3);
        }

        $data['time']['files'] = $fileTime;

        return new Commit($data);
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

        return Config::create()->fixers($fixers)->setDir($path)
            ->finder(DefaultFinder::create()->notName('*.blade.php')->in($path));
    }
}
