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

use Exception;

/**
 * This is the abstract persistence exception class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
abstract class AbstractPersistenceException extends Exception
{
    /**
     * The array of caught exceptions.
     *
     * @var \Exception[]
     */
    protected $exceptions;

    /**
     * Create a new persistence exception instance.
     *
     * @param \Exception[] $exceptions
     * @param string       $message
     *
     * @return void
     */
    public function __construct(array $exceptions, $message)
    {
        $this->exceptions = $exceptions;

        parent::__construct($message);
    }

    /**
     * Get the array of caught exceptions.
     *
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
