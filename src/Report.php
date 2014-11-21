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

use Gitonomy\Git\Diff\Diff;

/**
 * This is the report class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class Report
{
    /**
     * The project diff instance.
     *
     * @var \Gitonomy\Git\Diff\Diff
     */
    protected $diff;

    /**
     * The time taken to analyse the project.
     *
     * @var float
     */
    protected $time;

    /**
     * The memory used to analyse the project.
     *
     * @var float
     */
    protected $memory;

    /**
     * Create a report instance.
     *
     * @param \Gitonomy\Git\Diff\Diff $diff
     * @param float                   $time
     * @param float                   $memory
     *
     * @return void
     */
    public function __construct(Diff $diff, $time, $memory)
    {
        $this->diff = $diff;
        $this->time = $time;
        $this->memory = $memory;
    }

    /**
     * Get the analyser processing time.
     *
     * @return float
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * Get the analyser memory usage.
     *
     * @return float
     */
    public function memory()
    {
        return $this->memory;
    }

    /**
     * Get the get raw diff.
     *
     * @return string
     */
    protected function diff()
    {
        return $this->diff->getRawDiff();
    }

    /**
     * Get the modified files.
     *
     * @return array
     */
    public function files()
    {
        $this->diff->getFiles();
    }
}
