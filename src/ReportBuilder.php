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

use StyleCI\Git\Repositories\RepositoryInterface;
use StyleCI\Git\RepositoryFactory;

/**
 * This is the report builder class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ReportBuilder
{
    /**
     * The git repository factory instance.
     *
     * @var \StyleCI\Git\RepositoryFactory
     */
    protected $factory;

    /**
     * The cs analyser instance.
     *
     * @var \StyleCI\Fixer\Analyser
     */
    protected $analyser;

    /**
     * Create a report builder instance.
     *
     * @param \StyleCI\Git\RepositoryFactory $factory
     * @param \StyleCI\Fixer\Analyser        $analyser
     *
     * @return void
     */
    public function __construct(RepositoryFactory $factory, Analyser $analyser)
    {
        $this->factory = $factory;
        $this->analyser = $analyser;
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
        $repo = $this->factory->make($repo);

        $this->setup($repo, $commit);

        $data = $this->analyser->analyse($repo->path());

        return $this->buildReport($data, $repo);
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
