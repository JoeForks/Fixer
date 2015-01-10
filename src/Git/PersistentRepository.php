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

use Exception;
use Gitonomy\Git\Exception\GitExceptionInterface;
use StyleCI\Fixer\Git\Exceptions\Persistence\CloningRepositoryException;
use StyleCI\Fixer\Git\Exceptions\Persistence\FetchingRepositoryException;
use StyleCI\Fixer\Git\Exceptions\Persistence\GettingRepositoryException;
use StyleCI\Fixer\Git\Exceptions\Persistence\ResettingRepositoryException;
use StyleCI\Fixer\Git\Exceptions\RepositoryAlreadyExistsException;
use StyleCI\Fixer\Git\Exceptions\RepositoryDoesNotExistException;

/**
 * This is the persistent repository class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class PersistentRepository implements RepositoryInterface
{
    /**
     * The underlying repository instance.
     *
     * @var \StyleCI\Fixer\Git\RepositoryInterface
     */
    protected $repository;

    /**
     * Create a new persistent repository instance.
     *
     * @param \StyleCI\Fixer\Git\RepositoryInterface $repository
     *
     * @return void
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return the repository path on the local filesystem.
     *
     * @return string
     */
    public function path()
    {
        return $this->repository->path();
    }

    /**
     * Does this repository exist on the local filesystem?
     *
     * @return bool
     */
    public function exists()
    {
        return $this->repository->exists();
    }

    /**
     * Clone the repository to the local filesystem.
     *
     * @throws \StyleCI\Fixer\Git\Exceptions\Persistence\CloningRepositoryException
     *
     * @return void
     */
    public function get()
    {
        $exceptions = [];

        while ($tries = count($exceptions) < 3) {
            try {
                if ($tries > 0) {
                    // if we've failed before, delete the local repository
                    // because it may have been damaged in some way
                    $this->delete();
                }

                return $this->repository->get();
            } catch (RepositoryAlreadyExistsException $exception) {
                return; // we're totally done here, stop
            } catch (GitExceptionInterface $exception) {
                $exceptions[] = $exception;
            } catch (Exception $exception) {
                $exceptions[] = $exception;
                break; // if we get any other kind of exception, bail out
            }
        }

        throw new CloningRepositoryException($exceptions);
    }

    /**
     * Get the gitlib repository instance.
     *
     * @throws \StyleCI\Fixer\Git\Exceptions\Persistence\GettingRepositoryException
     *
     * @return \Gitonomy\Git\Repository
     */
    public function repo()
    {
        $exceptions = [];

        while ($tries = count($exceptions) < 3) {
            try {
                if ($tries > 0) {
                    // if we've failed before, delete the local repository
                    // because it may have been damaged in some way
                    $this->delete();
                }

                $this->get();

                return $this->repository->repo();
            } catch (RepositoryDoesNotExistException $exception) {
                $exceptions[] = $exception;
            } catch (GitExceptionInterface $exception) {
                $exceptions[] = $exception;
            } catch (Exception $exception) {
                $exceptions[] = $exception;
                break; // if we get any other kind of exception, bail out
            }
        }

        throw new GettingRepositoryException($exceptions);
    }

    /**
     * Fetch the latest changes to our repository from the interwebs.
     *
     * @throws \StyleCI\Fixer\Git\Exceptions\Persistence\FetchingRepositoryException
     *
     * @return void
     */
    public function fetch()
    {
        $exceptions = [];

        while ($tries = count($exceptions) < 3) {
            try {
                if ($tries > 0) {
                    // if we've failed before, delete the local repository
                    // because it may have been damaged in some way
                    $this->delete();
                    // we will then clone the repo again and stop
                    return $this->get();
                }

                return $this->repository->fetch();
            } catch (RepositoryDoesNotExistException $exception) {
                $exceptions[] = $exception;
            } catch (GitExceptionInterface $exception) {
                $exceptions[] = $exception;
            } catch (Exception $exception) {
                $exceptions[] = $exception;
                break; // if we get any other kind of exception, bail out
            }
        }

        throw new FetchingRepositoryException($exceptions);
    }

    /**
     * Reset our local repository to a specific commit.
     *
     * @param string $commit
     *
     * @throws \StyleCI\Fixer\Git\Exceptions\Persistence\ResettingRepositoryException
     *
     * @return void
     */
    public function reset($commit)
    {
        $exceptions = [];

        while ($tries = count($exceptions) < 3) {
            try {
                if ($tries > 0) {
                    // if we've failed before, delete the local repository
                    // because it may have been damaged in some way
                    $this->delete();
                }

                $this->get();

                return $this->repository->reset($commit);
            } catch (RepositoryDoesNotExistException $exception) {
                $exceptions[] = $exception;
            } catch (GitExceptionInterface $exception) {
                $exceptions[] = $exception;
            } catch (Exception $exception) {
                $exceptions[] = $exception;
                break; // if we get any other kind of exception, bail out
            }
        }

        throw new ResettingRepositoryException($exceptions);
    }

    /**
     * Get the diff for the uncommitted modifications.
     *
     * @return \Gitonomy\Git\Diff\Diff
     */
    public function diff()
    {
        $this->get();

        return $this->repository->diff();
    }

    /**
     * Delete our local repository from the local filesystem.
     *
     * Only do this if you really don't need it again because cloning it is
     * resource intensive, and can take a long time vs simply fetching the
     * latest changes in the future.
     *
     * @return void
     */
    public function delete()
    {
        return $this->repository->delete();
    }
}
