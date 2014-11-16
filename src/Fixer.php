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

use GrahamCampbell\Fixer\Models\Repo;
use GrahamCampbell\Fixer\Zip\Downloader;

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
     * The downloader instance.
     *
     * @var \GrahamCampbell\Fixer\Zip\Downloader
     */
    protected $downloader;

    /**
     * The storage path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a fixer instance.
     *
     * @param \GrahamCampbell\Fixer\Analyser       $analyser
     * @param \GrahamCampbell\Fixer\Zip\Downloader $downloader
     * @param string                               $path
     *
     * @return void
     */
    public function __construct(Analyser $analyser, Downloader $downloader, $path)
    {
        $this->analyser = $analyser;
        $this->downloader = $downloader;
        $this->path = $path;
    }

    /**
     * Analyse the commit and save the results.
     *
     * @param string $repo
     * @param string $commit
     *
     * @return \GrahamCampbell\Fixer\Models\Commit
     */
    public function analyse($repo, $commit)
    {
        $this->setup($repo, $commit);
        $data = $this->analyser->analyse($repo, $commit);
        $this->tearDown($commit);

        $repo = Repo::firstOrCreate(['id' => sha1($repo), 'name' => $repo]);
        $commit = $repo->commits->create(['id' => $commit, 'time' => $data['time'], 'memory' => $data['memory']]);
        $commit->files()->createMany($data['files']);

        return $commit;
    }

    /**
     * Set things up for analysis.
     *
     * @param string $repo
     * @param string $commit
     *
     * @return void
     */
    protected function setup($repo, $commit)
    {
        $path = $this->path.'/'.$commit;

        $archive = $this->downloader->download($repo.'/zip/'.$commit, $path.'.zip');

        $archive->extract($path);
        $archive->delete();
    }

    /**
     * Cleanup after analysis is complete.
     *
     * @param string $commit
     *
     * @return void
     */
    protected function tearDown($commit)
    {
        @unlink($this->path.'/'.$commit);
    }
}
