<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Fixer\Git;

/**
 * This is the repository interface.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RepositoryInterface
{
    /**
     * Return the repository path on the local filesystem.
     *
     * @return string
     */
    public function path();

    /**
     * Does this repository exist on the local filesystem?
     *
     * @return bool
     */
    public function exists();

    /**
     * Clone the repository to the local filesystem.
     *
     * @return void
     */
    public function get();

    /**
     * Get the gitlib repository instance.
     *
     * @return \Gitonomy\Git\Repository
     */
    public function repo();

    /**
     * Fetch the latest changes to our repository from the interwebs.
     *
     * @return void
     */
    public function fetch();

    /**
     * Reset our local repository to a specific commit.
     *
     * @param string $commit
     *
     * @return void
     */
    public function reset($commit);

    /**
     * Get the diff for the uncommitted modifications.
     *
     * @return \Gitonomy\Git\Diff\Diff
     */
    public function diff();

    /**
     * Delete our local repository from the local filesystem.
     *
     * Only do this if you really don't need it again because cloning it is
     * resource intensive, and can take a long time vs simply fetching the
     * latest changes in the future.
     *
     * @return void
     */
    public function delete();
}
