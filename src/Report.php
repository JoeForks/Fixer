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

use Gitonomy\Git\Diff\Diff;

/**
 * This is the report class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/Fixer/blob/master/LICENSE.md> AGPL 3.0
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
    public function diff()
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
        return $this->diff->getFiles();
    }

    /**
     * Was the analysis successful?
     *
     * @return bool
     */
    public function successful()
    {
        return empty($this->diff->getFiles());
    }
}
