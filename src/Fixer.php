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

use GrahamCampbell\Fixer\GitHub\Repository;

/**
 * This is the fixer class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Fixer/blob/master/LICENSE.md> Apache 2.0
 */
class Fixer
{
    /**
     * The analyser instance.
     *
     * @var \GrahamCampbell\Fixer\Analyser
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
     * @param \GrahamCampbell\Fixer\Analyser $analyser
     * @param string                         $path
     * @param array                          $options
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
     * @param string      $repo
     * @param string      $commit
     * @param string|null $cache
     *
     * @return array
     */
    public function analyse($repo, $commit, $cache = null)
    {
        $repo = $this->getRepo($repo);

        $this->setup($repo, $commit);

        $data = $this->analyser->analyse($repo->path(), $cache);

        return $this->buildReport($data, $repo);
    }

    /**
     * Get the github repo instance.
     *
     * @param string $repo
     *
     * @return \GrahamCampbell\Fixer\GitHub\Repository
     */
    protected function getRepo($repo)
    {
        return new Repository($repo, $this->path, $this->options);
    }

    /**
     * Set things up for analysis.
     *
     * @param \GrahamCampbell\Fixer\GitHub\Repository $repo
     * @param string                                  $commit
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
     * @param array                                   $data
     * @param \GrahamCampbell\Fixer\GitHub\Repository $repo
     *
     * @return \GrahamCampbell\Fixer\Report
     */
    protected function buildReport(array $data, Repository $repo)
    {
        return new Report($repo->diff(), $data['time'], $data['memory']);
    }
}
