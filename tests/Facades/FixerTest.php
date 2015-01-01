<?php

/*
 * This file is part of StyleCI Fixer.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StyleCI\Tests\Fixer\Facades;

use GrahamCampbell\TestBench\Traits\FacadeTestCaseTrait;
use StyleCI\Tests\Fixer\AbstractTestCase;

/**
 * This is the fixer facade test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class FixerTest extends AbstractTestCase
{
    use FacadeTestCaseTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'fixer';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return 'StyleCI\Fixer\Facades\Fixer';
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return 'StyleCI\Fixer\Fixer';
    }
}
