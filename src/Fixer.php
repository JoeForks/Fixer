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

use StyleCI\Fixer\GitHub\Repository;

/**
 * This is the fixer class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/StyleCI/Fixer/blob/master/LICENSE.md> AGPL 3.0
 */
class Fixer
{
    /**
     * The analyser instance.
     *
     * @var \StyleCI\Fixer\Analyser
     */
    protected $analyser;

    /**
     * The storage path.
     *
     * @var string
     */
    protected $path;

    /**
     * The gitlib options.
     *
     * @var array
     */
    protected $options;

    /**
     * Create a fixer instance.
     *
     * @param \StyleCI\Fixer\Analyser $analyser
     * @param string                  $path
     * @param array                   $options
     *
     * @return void
     */
    public function __construct(Analyser $analyser, $path, array $options)
    {
        $this->analyser = $analyser;
        $this->path = $path;
        $this->options = $options;
    }

    /**
     * Analyse the commit and return the results.
     *
     * @param string $repo
     * @param string $commit
     *
     * @return \StyleCI\Fixer\Report
     */
    public function analyse($repo, $commit)
    {
        $repo = $this->getRepo($repo);

        $this->setup($repo, $commit);

        $data = $this->analyser->analyse($repo->path());

        return $this->buildReport($data, $repo);
    }

    /**
     * Get the github repo instance.
     *
     * @param string $repo
     *
     * @return \StyleCI\Fixer\GitHub\Repository
     */
    protected function getRepo($repo)
    {
        return new Repository($repo, $this->path, $this->options);
    }

    /**
     * Set things up for analysis.
     *
     * @param \StyleCI\Fixer\GitHub\Repository $repo
     * @param string                           $commit
     *
     * @return void
     */
    protected function setup(Repository $repo, $commit)
    {
        if (!$repo->exists()) {
            $repo->get();
        }

        $repo->fetch();

        $repo->reset($commit);
    }

    /**
     * Build the fixer report.
     *
     * @param array                            $data
     * @param \StyleCI\Fixer\GitHub\Repository $repo
     *
     * @return \StyleCI\Fixer\Report
     */
    protected function buildReport(array $data, Repository $repo)
    {
        return new Report($repo->diff(), $data['time'], $data['memory']);
    }
}
