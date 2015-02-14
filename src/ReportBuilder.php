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

use StyleCI\Git\Repositories\GitHubRepository;
use StyleCI\Git\Repositories\PersistentRepository;
use StyleCI\Git\Repositories\RepositoryInterface;

/**
 * This is the report builder class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ReportBuilder
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
     * Create a report builder instance.
     *
     * @param \StyleCI\Fixer\Analyser $analyser
     * @param string                  $path
     *
     * @return void
     */
    public function __construct(Analyser $analyser, $path)
    {
        $this->analyser = $analyser;
        $this->path = $path;
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
     * @return \StyleCI\Git\Repositories\PersistentRepository
     */
    public function getRepo($repo)
    {
        $repository = new GitHubRepository($repo, $this->path);

        return new PersistentRepository($repository);
    }

    /**
     * Set things up for analysis.
     *
     * @param \StyleCI\Git\Repositories\RepositoryInterface $repo
     * @param string                                        $commit
     *
     * @return void
     */
    protected function setup(RepositoryInterface $repo, $commit)
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
     * @param array                                         $data
     * @param \StyleCI\Git\Repositories\RepositoryInterface $repo
     *
     * @return \StyleCI\Fixer\Report
     */
    protected function buildReport(array $data, RepositoryInterface $repo)
    {
        return new Report($repo->diff(), $data['time'], $data['memory']);
    }
}
