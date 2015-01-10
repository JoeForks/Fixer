<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Fixer\Git\Exceptions\Persistence;

/**
 * This is the resetting repository exception class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ResettingRepositoryException extends AbstractPersistenceException
{
    /**
     * Create a new resetting repository exception instance.
     *
     * @param \Exception[] $exceptions
     *
     * @return void
     */
    public function __construct(array $exceptions)
    {
        parent::__construct($exceptions, 'Resetting the repository has failed.');
    }
}
