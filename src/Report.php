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

use Gitonomy\Git\Diff\Diff;

/**
 * This is the report class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
